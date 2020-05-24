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
     * @var $status_id int
     */
    public $status_id;

    /**
     * @var $queueId
     */
    protected $queueId;

    public function __construct( int $id ) {

        /*
        $db = Database::getInstance()->connection();

        $query = "SELECT l.*, 
                         q.id as queue_id
                  FROM locations as l
                  LEFT JOIN queues as q 
                    ON (l.id = q.location_id AND q.status_id = ?)
                  WHERE l.id = ?";

        $params = [ self::STATUS_ACTIVE, $id ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query, $params);
        $location  = $statement->fetch(PDO::FETCH_ASSOC);
        */

        // Retrieve location from the database
        $location = [
            'id' => $id,
            'name' => 'UPS Store ' . $id,
            'address1' => '123 Testing Drive',
            'address2' => 'Suite 456',
            'city' => 'Mesa',
            'state' => 'AZ',
            'zip' => '85209',
            'phone' => '4805553333',
            'status_id' => 1,
            'queue_id' => 1
        ];
        
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

    /**
     * Does the location have an active queue
     */
    public function hasActiveQueue() {
        return ($this->queueId !== 0);
    }

    /**
     * Retrieve the total count of queue items by status
     */
    public function getQueueItemCountByStatus( int $status_id = 1 ) {

        return 5;
        
        if ($this->hasActiveQueue() === false) {
            return false;
        }

        // Query the database to retrieve the current position of the queue counter
        $query = "SELECT COUNT(qi.id) 
                  FROM queue_items as qi 
                  WHERE queue_id = ? AND qi.status_id = ?";

        $params = [ $this->queueId, $status_id ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query, $params);
        $items = $statement->fetchColumn();

        return $items;

    }

    /**
     * Retrieve the current overall queue position
     */
    public function getCurrentQueuePosition() {

        return 5;
        
        if ($this->hasActiveQueue() === false) {
            return false;
        }

        // Query the database to retrieve the current position of the queue counter
        $query = "SELECT COUNT(qi.id) 
                  FROM queue_items as qi 
                  WHERE queue_id = ? AND qi.status_id IN (?, ?)";

        $params = [ $this->queueId, self::STATUS_ACTIVE, self::STATUS_COMPLETED ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query, $params);
        $position = $statement->fetchColumn();

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
        $query = "SELECT qi.position
                  FROM queue_items as qi
                  INNER JOIN queues as q
                    ON (q.id = qi.queue_id AND q.location_id = ?)
                  WHERE qi.token = ?";
        
        $params = [ $this->id, $token ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query, $params);
        $position = $statement->fetchColumn();  

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

        // Insert a new record into the queue table
        $query = 'INSERT INTO queue_items 
                  (queue_id, position, status_id, token)
                  (SELECT queue_id, COUNT(id), queue_id, ? FROM queue_items WHERE  )';

        $params = [$this->queueId, self::STATUS_PENDING, $token];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query, $params);
        $result = $statement->execute();

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

            $query = 'UPDATE queue_items SET position = position - 1
                    WHERE status_id = ? AND queue_id = ?';

            $params = [self::STATUS_PENDING, $this->queueId];

            $statement = $db->prepare($query, $params);
            $statement->execute();

            $query = 'UPDATE queue_items SET status_id = ?
                    WHERE status_id = ? AND queue_id = ?';

            $params = [self::STATUS_COMPLETED, self::STATUS_ACTIVE, $this->queueId];

            $statement = $db->prepare($query, $params);
            $statement->execute();

            $query = 'UPDATE queue_items SET status_id = ?
                    WHERE id = (
                        SELECT id 
                        FROM queue_items 
                        WHERE queue_id = ? AND status_id = ?
                        ORDER BY id ASC
                        LIMIT 1
                    )';

            $params = [self::STATUS_ACTIVE, $this->queueId, self::STATUS_PENDING];

            $statement = $db->prepare($query, $params);
            $statement->execute();

            $db->commit();

            return true;

        } catch (Exception $e) {

            $db->rollBack();

            return false;

        }
        
    }

    /**
     * For all the items that are after the canceled item, we will want to reduce their
     * position in the queue.
     */
    public function cancelItemInQueue( $token ) {

        $db = Database::getInstance()->connection();

        $query = "SELECT id FROM queue_items WHERE token = ? AND status_id = ?";
        $statement = $db->prepare($query, [ $token, self::STATUS_PENDING ]);
        $itemId = $statement->fetchColumn();

        if ($itemId) {
            // Cancel the queue item
            $query = 'UPDATE queue_items 
                      SET status_id = ? 
                      WHERE status_id = ? AND id = ?';

            $params = [self::STATUS_CANCELED, self::STATUS_PENDING, $itemId];

            $statement = $db->prepare($query, $params);
            $result = $statement->execute();

            if ( $result ) {

                // Update all queue items after it to reduce their queue position by 1
                $query = 'UPDATE queue_items SET position = position - 1
                        WHERE status_id = ? AND queue_id = ? AND id > ?';

                $params = [self::STATUS_PENDING, $this->queueId, $itemId];

                $statement = $db->prepare($query, $params);
                $statement->execute();

            }

        } else {

            return false;

        }

    }

    public function getEstimatedWaitTime()
    {
        // Hard coding 2.5 minutes
        $averageWaitTimePerQueueItem = 2.5 * 60; // Avg. number of seconds per queue item

        return $this->getQueueItemCountByStatus() * $averageWaitTimePerQueueItem;
    }

}