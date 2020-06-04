<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Location {

    const STATUS_PENDING   = 1;
    const STATUS_ACTIVE    = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELED  = 4;

    /**
     * @var $id int
     */
    public $id;
    /**
     * @var $name string
     */
    public $name;

    /**
     * @var $address1 string
     */
    public $address1;

    /**
     * @var $address2 string
     */
    public $address2;

    /**
     * @var $city string
     */
    public $city;

    /**
     * @var $state string
     */
    public $state;

    /**
     * @var $zip string
     */
    public $zip;

    /**
     * @var $phone string
     */
    public $phone;

    /**
     * @var $statusId int
     */
    public $statusId;

    /**
     * @var $queueId
     */
    protected $queueId;

    public function __construct( int $id ) {

        $query = "SELECT l.*, 
                         q.id as queue_id
                  FROM locations as l
                  LEFT JOIN queues as q 
                    ON (l.id = q.location_id AND q.status_id = ?)
                  WHERE l.id = ?
                  LIMIT 1";

        $params = [ self::STATUS_ACTIVE, $id ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query);
        $result    = $statement->execute($params);
        $location  = $statement->fetch(PDO::FETCH_ASSOC);
        
        if(!$location) {
            throw new Exception('Location was not found with ID: ' . $id);
        }

        $this->id = $location['id'];
        $this->name = $location['name'];
        $this->address1 = $location['address1'];
        $this->address2 = $location['address2'];
        $this->city = $location['city'];
        $this->state = $location['state'];
        $this->zip = $location['zip'];
        $this->phone = $location['phone'];
        $this->statusId = $location['status_id'];
        $this->queueId = ($location['queue_id']) ? $location['queue_id'] : 0;

    }

    public function getFormattedAddress() {
        $address[] = $this->address1;
        if ( $this->address2 ) {
            $address[] = $this->address2;
        }
        $address[] = $this->city . ', ' . $this->state . ' ' . $this->zip;
        return implode('<br>', $address);
    }

    public function getFormattedStatus()
    {
        switch ( $this->statusId ) {
            case self::STATUS_ACTIVE:
                $status = 'Active'; 
            break;
            case self::STATUS_PENDING:
                $status = 'Pending'; 
            break;
            case self::STATUS_COMPLETED:
                $status = 'Completed'; 
            break;
            case self::STATUS_CANCELED:
                $status = 'Canceled'; 
            break;
            default:
                $status = 'Unknown';
        }

        return $status;
    }

    /**
     * Does the location have an active queue
     */
    public function hasActiveQueue() {
        return ($this->queueId !== 0);
    }

    /**
     * Retrieve the total count of queue items by status
     * @param int $statusId
     * @return int
     */
    public function getQueueItemCountByStatus( int $statusId = self::STATUS_PENDING ) {

        if ($this->hasActiveQueue() === false) {
            return false;
        }

        // Query the database to retrieve the current position of the queue counter
        $query = "SELECT COUNT(qi.id) 
                  FROM queue_items as qi 
                  WHERE queue_id = ? AND qi.status_id = ?";

        $params = [ $this->queueId, $statusId ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query);
        $result    = $statement->execute($params);
        $items     = $statement->fetch(PDO::FETCH_COLUMN);

        return $items;

    }

    /**
     * Retrieve the current overall queue position
     */
    public function getCurrentQueuePosition() {

        if ($this->hasActiveQueue() === false) {
            return false;
        }

        // Query the database to retrieve the current position of the queue counter
        $query = "SELECT COUNT(qi.id) 
                  FROM queue_items as qi 
                  WHERE queue_id = ? AND qi.status_id IN (?, ?)";

        $params = [ $this->queueId, self::STATUS_ACTIVE, self::STATUS_COMPLETED ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query);
        $result    = $statement->execute($params);
        $position  = $statement->fetch(PDO::FETCH_COLUMN);

        return ($position > 0) ? $position : 1;

    }

    /**
     * Retrieve the current queue position for the provided token
     */
    public function getCurrentQueuePositionByToken( $token ) {

        if ($this->hasActiveQueue() === false) {
            return false;
        }

        // Query the database to retrieve the current position of the queue counter
        $query = "SELECT qi.queue_position
                  FROM queue_items as qi
                  INNER JOIN queues as q
                    ON (q.id = qi.queue_id AND q.location_id = ?)
                  WHERE qi.token = ?";
        
        $params = [ $this->id, $token ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query);
        $result    = $statement->execute($params);
        $position  = $statement->fetch(PDO::FETCH_COLUMN);

        return is_numeric($position) ? $position : false;

    }

    /**
     * Add a new item to the queue. If successful, return the token, otherwise false.
     * 
     * @return false|string
     */
    public function addNewQueueItem()
    {
        $token = uniqid('qm-', true);

        $db = Database::getInstance()->connection();
        $db->beginTransaction();
        $db->exec('LOCK TABLE queue_items IN ACCESS EXCLUSIVE MODE');
        
        $query     = "SELECT COUNT(id) FROM queue_items WHERE queue_id = ? AND status_id = ? GROUP BY queue_id";
        $params    = [ $this->queueId, self::STATUS_PENDING ];
        $statement = $db->prepare($query);
        $statement->execute($params);
        $position  = $statement->fetchColumn();

        $query     = 'INSERT INTO queue_items (queue_id, queue_position, status_id, token) VALUES(?, ?, ?, ?)';
        $params    = [ $this->queueId, ($position) ? $position : 1, self::STATUS_PENDING, $token];
        $statement = $db->prepare($query);
        $result    = $statement->execute($params);

        $db->commit();
 
        return ($result) ? $token : false;
    }

    /**
     * Set any active status items in the queue to completed and set the first pending
     * item in the queue to now be active.
     * 
     * Reduce the position of all later queue items.
     */
    public function setNextInQueueActive()
    {
        $db = Database::getInstance()->connection();

        try {

            $db->beginTransaction();

            $query = 'UPDATE queue_items SET queue_position = queue_position - 1
                      WHERE status_id = ? AND queue_id = ?';

            $params = [self::STATUS_PENDING, $this->queueId];

            $statement = $db->prepare($query);
            $result    = $statement->execute($params);

            $query = 'UPDATE queue_items SET status_id = ?
                      WHERE status_id = ? AND queue_id = ?';

            $params = [self::STATUS_COMPLETED, self::STATUS_ACTIVE, $this->queueId];

            $statement = $db->prepare($query);
            $result    = $statement->execute($params);

            $query = 'UPDATE queue_items SET status_id = ?
                      WHERE id = (
                        SELECT id 
                        FROM queue_items 
                        WHERE queue_id = ? AND status_id = ?
                        ORDER BY id ASC
                        LIMIT 1
                    )';

            $params = [self::STATUS_ACTIVE, $this->queueId, self::STATUS_PENDING];

            $statement = $db->prepare($query);
            $result    = $statement->execute($params);

            $db->commit();

            return true;

        } catch (Exception $e) {

            $db->rollBack();

            return false;

        }
        
    }

    /**
     * Check if a user is allowed to admin the current location.
     */
    public function isUserLocationAdmin( $userId ) {

        $db = Database::getInstance()->connection();

        $query  = "SELECT * FROM location_user WHERE location_id = ? AND user_id = ?";
        $params = [$this->id, $userId];

        $statement = $db->prepare($query);
        $result    = $statement->execute($params);

        return ($statement->fetch(PDO::FETCH_ASSOC)) ? true : false;
    }


    /**
     * For all the items that are after the canceled item, we will want to reduce their
     * position in the queue.
     */
    public function cancelItemInQueue( $token ) {

        $db = Database::getInstance()->connection();

        $query  = "SELECT id FROM queue_items WHERE token = ? AND status_id = ?";
        $params = [ $token, self::STATUS_PENDING ];

        $statement = $db->prepare($query);
        $result    = $statement->execute($params);
        $itemId    = $statement->fetch(PDO::FETCH_COLUMN);

        if ($itemId) {
            // Cancel the queue item
            $query = 'UPDATE queue_items 
                      SET status_id = ? 
                      WHERE status_id = ? AND id = ?';

            $params = [self::STATUS_CANCELED, self::STATUS_PENDING, $itemId];

            $statement = $db->prepare($query);
            $result    = $statement->execute($params);

            if ( $result ) {

                // Update all queue items after it to reduce their queue position by 1
                $query = 'UPDATE queue_items SET queue_position = queue_position - 1
                        WHERE status_id = ? AND queue_id = ? AND id > ?';

                $params = [self::STATUS_PENDING, $this->queueId, $itemId];

                $statement = $db->prepare($query);
                $result    = $statement->execute($params);

            }

        } else {

            return false;

        }

    }

    public function getEstimatedWaitTime( $format = 'seconds', $token = null )
    {
        // Hard coding 2.5 minutes
        $averageWaitTimePerQueueItem = 2.5 * 60; // Avg. number of seconds per queue item
        $position = ($token) ? $this->getCurrentQueuePositionByToken($token) : $this->getQueueItemCountByStatus();
        
        $time = $position * $averageWaitTimePerQueueItem;

        if ( $format === 'minutes' ) {
            if ( $time > 0 ) {
                $time = $time / 60;
            }
        }

        return $time;

    }

}