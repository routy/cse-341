<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class User {

    public function __construct( $id ) {

    }

    /**
     * 
     */
    public static function isLoggedIn()
    {
        $session = Session::getInstance();
        return $session->has('userId');
    }

    /**
     * 
     */
    public static function getSessionLocationToken($locationId)
    {
        $session = Session::getInstance();
        $token = $session->get( $locationId . '_token');
        return ($token) ? $token : false;
    }

    /**
     * 
     */
    public static function getCurrentUserId()
    {
        $session = Session::getInstance();
        return ($session->has('userId')) ? $session->get('userId') : false;
    }

    /**
     * 
     */
    public static function getCurrentUser()
    {
        if ( $userId = self::getCurrentUserId() !== false ) {
    
            $database = Database::getInstance();
            $query = "SELECT u.*
                      FROM users as u
                      WHERE u.id = ?
                      LIMIT 1";
    
            $params = [ $userId ];
    
            $db = Database::getInstance()->connection();
            $statement = $db->prepare($query);
            $statement->execute($params);
            $user      = $statement->fetch(PDO::FETCH_ASSOC);
    
            return $user;
        } 
    
        return false;

    }

    /**
     * @return void
     */
    public static function logout()
    {
        $session = Session::getInstance();

        $session->remove('userId');
    }

    /**
     * @return bool
     */
    public static function login( $email, $password ) {

        $query  = "SELECT * FROM users WHERE email = ?";
        $params = [$email];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query);
        $statement->execute($params);
        $user      = $statement->fetch(PDO::FETCH_ASSOC);

        $session = Session::getInstance();

        if ( $user && password_verify( $password, $user['password'] ) ) {

            $session->store('userId', $user['id']);
            
            return true;

        } else {

            Message::add( 'Your email or password are incorrect. Please try again.', 'danger');
            
            return false;
        }

    }

    public static function register( $formValues )
    {
        $register = [];

        $required = [
            'email', 'password', 'confirm_password', 'first_name', 'last_name', 'name', 'address', 'city', 'state', 'zip', 'phone'
        ];
        $errors = false;

        foreach( $formValues as $field => $value ) {
            if ( in_array( $field, $required ) ) {
                if ( isset( $formValues[$field] ) && !empty( $formValues[$field] ) ) {
                    if ($field === 'phone') {
                        $register[$field] = preg_replace('/[^0-9]/s', '', $formValues[$field]);
                    } else {
                        $register[$field] = filter_var( $formValues[$field],FILTER_SANITIZE_STRING );
                    }
                } else {
                    $errors = true;
                    Message::add( ucwords(str_replace('_', ' ', $field)) . ' is a required field.', 'danger' );
                }
            } else {
                $field_value = filter_var( $formValues[$field],FILTER_SANITIZE_STRING );
                $register[$field] = (!empty($field_value)) ? $field_value : null;
            }

            if ($field === 'password' && $formValues[$field] !== $formValues['confirm_password'] ) {
                $errors = true;
                Message::add( 'Your passwords do not match.', 'danger' );
            }

            if ($field === 'phone' && strlen($register[$field]) !== 10 ) {
                $errors = true;
                Message::add( 'Please enter a valid phone number. Digits only, 10 numbers in length.', 'danger' );
            }
        }

        if (!$errors) {

            $db = Database::getInstance()->connection();

            try {

                $db->beginTransaction();

                $query     = "SELECT * FROM users WHERE email = ?";
                $statement = $db->prepare($query);
                $statement->execute([$register['email']]);
                $user      = $statement->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    Message::add('Your email address is already registered with our service. Please login.', 'danger');
      
                    redirect('login.php');
                }

                $query = "INSERT INTO users (first_name, last_name, email, password) 
                          VALUES (?, ?, ?, ?)";
                $params = [
                    $register['first_name'],
                    $register['last_name'],
                    $register['email'],
                    password_hash($register['password'], PASSWORD_BCRYPT)
                ];
            
                $statement = $db->prepare($query);
                $result    = $statement->execute($params);
                $userId    = $db->lastInsertId();

                $query = "INSERT INTO locations (name, address1, address2, city, state, zip, phone, status_id) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $params = [
                    $register['name'],
                    $register['address'],
                    $register['address2'],
                    $register['city'],
                    $register['state'],
                    $register['zip'],
                    $register['phone'],
                    Location::STATUS_ACTIVE
                ];

                $statement  = $db->prepare($query);
                $result     = $statement->execute($params);
                $locationId = $db->lastInsertId();

                $query = "INSERT INTO location_user (location_id, user_id) VALUES (?, ?)";
                $params = [$locationId, $userId];

                $statement  = $db->prepare($query);
                $result     = $statement->execute($params);

                $db->commit();

                Message::add('Your account has been created successfully.');

                redirect('login.php');

            } catch(Exception $e) {

                $db->rollback();

                Message::add('An error occurred while processing your request. Please try again later.', 'danger');

            }
        }

        return false;

    }

}