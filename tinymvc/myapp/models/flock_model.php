<?php

class Flock_Model extends TinyMVC_Model
{
    /**
     * Returns a list of the sheep in the flock.
     */
    function get_sheep($flock)
    {
        $this->db->query_all('select * from sheep where flock_id=?', $flock, PDO::FETCH_ASSOC);
    }
}