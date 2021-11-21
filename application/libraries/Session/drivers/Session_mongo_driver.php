<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Session MongoDB Driver
 *
 * @package CodeIgniter
 * @subpackage  Libraries
 * @category    Sessions
 * @author  Intekhab Rizvi
 * @modify hao.nguyen
 * @link    https://codeigniter.com/user_guide/libraries/sessions.html
 * @repo-url https://github.com/intekhabrizvi/codeigniter-mongodb-session-driver.git
 */
 # Set TTL based indexed in your MongoDB's Collection. Default value is 1 hour, you can set whatever you want in below query
 # db.YOUR_COLELCTION_NAME.createIndex( { "timestamp": 1 }, { expireAfterSeconds: 3600 } )

class CI_Session_mongo_driver extends CI_Session_driver implements SessionHandlerInterface {

    private $_ST;

    /**
     * DB object
     *
     * @var object
     */
    protected $_db;

    /**
     * Name of MongoDB database & collection holding all session data
     * @var string
     */
    protected $db_name;
    protected $collection;

    /**
     * Row exists flag
     *
     * @var bool
     */
    protected $_row_exists = FALSE;

    // ------------------------------------------------------------------------


    /**
     * Class constructor
     *
     * @param   array   $params Configuration parameters
     * @return  void
     */
    public function __construct(&$params)
    {

        parent::__construct($params);
        $this->_ST = &get_instance();
        
        if ( ! isset($this->_config['save_table']))
        {
            throw new Exception('Missing sess_save_table setting in application/config.php file.');
            
        }

        try
        {
          
            $this->_db = $this->_ST->mongo_db;
            $this->collection = $this->_config['save_table'];
        }
        catch (Exception $e)
        {
            throw new Exception("Unable to connect to MongoDB Server: {$e->getMessage()}");
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Open
     *
     * Initializes the database connection
     *
     * @param   string  $save_path  Table name
     * @param   string  $name       Session cookie name, unused
     * @return  bool
     */
    public function open($save_path, $name)
    {
        if (empty($this->_db))
        {
            return $this->_fail();
        }

        return $this->_success;
    }

    // ------------------------------------------------------------------------

    /**
     * Read
     *
     * Reads session data and acquires a lock
     *
     * @param   string  $session_id Session ID
     * @return  string  Serialized session data
     */
    public function read($session_id)
    {
        // Needed by write() to detect session_regenerate_id() calls
            $this->_session_id = $session_id;

            $where['sess_id'] = $this->_session_id;

            if ($this->_config['match_ip'])
            {
                $where['ip_address'] = $this->get_client_ip();
            }


            $query = $this->_ST->mongo_db->where($where)->get($this->collection);

            if ( count($query) === 0)
            {
                // PHP7 will reuse the same SessionHandler object after
                // ID regeneration, so we need to explicitly set this to
                // FALSE instead of relying on the default ...
                $this->_row_exists = FALSE;
                $this->_fingerprint = md5('');
                return '';
            }

            $this->_fingerprint = md5($query[0]['data']);
            $this->_row_exists = TRUE;
            unset($where);
            return $query[0]['data'];
    }

    // ------------------------------------------------------------------------

    /**
     * Write
     *
     * Writes (create / update) session data
     *
     * @param   string  $session_id Session ID
     * @param   string  $session_data   Serialized session data
     * @return  bool
     */
    public function write($session_id, $session_data)
    {

        // Was the ID regenerated?
        if (isset($this->_session_id) && $session_id !== $this->_session_id)
        {
            $this->_row_exists = FALSE;
            $this->_session_id = $session_id;
        }

        if ($this->_row_exists === FALSE)
        {
            $insert_data = array(
                'sess_id' => $session_id,
                'ip_address' => $this->get_client_ip(),
                'timestamp' => new MongoDB\BSON\UTCDateTime(),
                'user_agent' => $this->_ST->input->user_agent(),
                'data' => $session_data
            );

            $write = $this->_ST->mongo_db->insert($this->collection, $insert_data);
            
            if ($write)
            {
                $this->_fingerprint = md5($session_data);
                $this->_row_exists = TRUE;
                return $this->_success;
            }

            return $this->_fail();
        }

        $where['sess_id'] = $session_id;
        if ($this->_config['match_ip'])
        {
            $where['ip_address'] = $this->get_client_ip();
        }

        $update_data = array('timestamp' => new MongoDB\BSON\UTCDateTime());
        if ($this->_fingerprint !== md5($session_data))
        {
            $update_data['data'] = $session_data;
        }


        $update = $this->_ST->mongo_db->where($where)->set($update_data)->update($this->collection);

        if ($update)
        {
            $this->_fingerprint = md5($session_data);
            return $this->_success;
        }

        return $this->_fail();
    }

    // ------------------------------------------------------------------------

    /**
     * Close
     *
     * Releases locks
     *
     * @return  bool
     */
    public function close()
    {
        return $this->_success;
    }

    // ------------------------------------------------------------------------

    /**
     * Destroy
     *
     * Destroys the current session.
     *
     * @param   string  $session_id Session ID
     * @return  bool
     */
    public function destroy($session_id)
    {
        $where['sess_id'] = $session_id;
        if ($this->_config['match_ip'])
        {
            $where['ip_address'] = $this->get_client_ip();
        }

        $write = $this->_ST->mongo_db->where($where)->delete($this->collection);

        /*if ( $write->getDeletedCount() == 0 )
        {
            return $this->_fail();
        }*/
        
        if ($this->close() === $this->_success)
        {
            $this->_cookie_destroy();
            return $this->_success;
        }

        return $this->_success;
    }

    // ------------------------------------------------------------------------

    /**
     * Garbage Collector
     *
     * Deletes expired sessions
     * Not required as document expiry will be taken by MongoDB collection TTL
     *
     * @param   int     $maxlifetime    Maximum lifetime of sessions
     * @return  bool
     */
    public function gc($maxlifetime)
    {

        return $this->_success;
    }

    // --------------------------------------------------------------------

    /**
     * Validate ID
     *
     * Checks whether a session ID record exists server-side,
     * to enforce session.use_strict_mode.
     *
     * @param   string  $id
     * @return  bool
     */
    public function validateId($id)
    {
        $where['sess_id'] = $id;

        if ($this->_config['match_ip'])
        {
            $where['ip_address'] = $this->get_client_ip();
        }

        $query = $this->_ST->mongo_db->where($where)->get($this->collection);

        if ( count($query) === 1)
        {
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Get lock
     *
     * Acquires a lock, depending on the underlying platform.
     * Not required, MongoDB's WiredTiger storage engine maintain read/write lock
     * pretty well.
     *
     * @param   string  $session_id Session ID
     * @return  bool
     */
    protected function _get_lock($session_id)
    {
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Release lock
     *
     * Releases a previously acquired lock
     * Not required, MongoDB's WiredTiger storage engine maintain read/write lock
     * pretty well.
     *
     * @return  bool
     */
    protected function _release_lock()
    {
        return true;
    }
    private function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}
