<?php

/*
 * sync*gw RoundCube Bundle
 *
 * @copyright  http://syncgw.com, 2017 - 2019
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

class roundcube_select_for_sync extends rcube_plugin {

	// full address book
	const ABOOK_FULL  = 'A';
	// only contacts with tlephone number assigned
	const ABOOK_SMALL = 'P';
	// full calendar
	const CAL_FULL    = 'C';
	// full task list
	const TASK_FULL   = 'T';
    // max. number to check
    const MAX         = 99;

    public  $task     = 'settings';
	private $cal_db   = null;
	private $tsk_db   = null;
	private $rc;

	/**
	 *	Initialize plugin
	 * 	@see rcube_plugin::init()
	 */
	function init() {
        $this->add_texts('localization/', false);

        $this->add_hook('preferences_sections_list', [ $this, 'select_for_sync_section' ]);
        $this->add_hook('preferences_list',          [ $this, 'select_for_sync_pref' ]);
		$this->add_hook('preferences_save',          [ $this, 'select_for_sync_save_pref' ]);

		$this->rc = rcmail::get_instance();
 	}

  	/**
 	 *  Add a section to the preference tab
 	 *
	 *	@param 	array $args: section list
	 *	@return array $args
 	 */
    public function select_for_sync_section($args) {

        $this->add_texts('localization/', false);

        $args['list']['select_for_sync_pref'] = [
                        'id'      => 'select_for_sync_pref',
                        'section' => rcube::Q($this->gettext('syncgw_p_title')),
        ];

        return $args;
    }

	/**
	 * 	Adds a checkbox selection list for synchronization
	 *
	 *	@param 	array $args: section list
	 *	@return array $args
	 */
    public function select_for_sync_pref($args) {

		if ($args['section'] != 'select_for_sync_pref')
		    return $args;

        $prefs = $this->rc->user->get_prefs();
        $prefs = $prefs['syncgw'];

	    // address books
        $args['blocks']['syncgw_a']['name'] = $this->gettext('syncgw_a_head');

        $n = 0;
    	foreach ($this->rc->get_address_sources() as $a) {
            $c = new html_checkbox([
			         'name'     => '_syncgw_a'.$n,
    				 'id' 	    => '_syncgw_a'.$n,
	       			 'value'    => self::ABOOK_FULL.$a['id'],
			]);
            $p = new html_checkbox([
			         'name'     => '_syncgw_p'.$n,
    				 'id' 	    => '_syncgw_p'.$n,
	       			 'value'    => self::ABOOK_SMALL.$a['id'],
			]);
            $args['blocks']['syncgw_a']['options']['syncgw'.$n++] = [
	       			'title' 	=> rcube::Q('"'.$a['name'].'"'),
			     	'content' 	=> $c->show(strpos($prefs, self::ABOOK_FULL.$a['id'].';') !== false ? self::ABOOK_FULL.$a['id'] : null).' '.
                                   rcube::Q($this->gettext('syncgw_a_tonly')).' '.
                                   $p->show(strpos($prefs, self::ABOOK_SMALL.$a['id'].';') !== false ? self::ABOOK_SMALL.$a['id'] : null),
            ];
		}

		// calendars
        $args['blocks']['syncgw_c']['name'] = $this->gettext('syncgw_c_head');

        if (!is_object($this->cal_db)) {

            // calendar.php:function load_driver() -- START

        	if ($cal = $this->rc->plugins->get_plugin('calendar')) {
	            $n = $this->rc->config->get('calendar_driver', 'database');
    	        $c = $n.'_driver';

        	    require_once($cal->home.'/drivers/calendar_driver.php');
            	require_once($cal->home.'/drivers/'.$n.'/'.$c.'.php');

	            $this->cal_db = new $c($cal);
    	        // -- END
        	}
        }

		$n = 0;
		if ($this->cal_db) {
	        foreach ($this->cal_db->list_calendars(calendar_driver::FILTER_PERSONAL | calendar_driver::FILTER_WRITEABLE) as $a) {
    	        if (!$a['active'])
        	        continue;
	            	$c = new html_checkbox([
    					'name' 	    => '_syncgw_c'.$n,
	   			    	'id' 	    => '_syncgw_c'.$n,
					    'value'     => self::CAL_FULL.$a['id'],
	            ]);
	    	    $args['blocks']['syncgw_c']['options']['syncgw'.$n++] = [
		       			'title' 	=> rcube::Q('"'.$a['name'].'"'),
				     	'content' 	=> $c->show(strpos($prefs, self::CAL_FULL.$a['id'].';') !== false ? self::CAL_FULL.$a['id'] : null),
				];
	        }
		}

		// task lists
        $args['blocks']['syncgw_t']['name'] = $this->gettext('syncgw_t_head');

        if (!is_object($this->tsk_db)) {

            // tasklist.php:function load_driver() -- START

        	if ($tsk = $this->rc->plugins->get_plugin('tasklist')) {
	            $n   = $this->rc->config->get('tasklist_driver', 'database');
    	        $c   = 'tasklist_' . $n . '_driver';

        	    require_once($tsk->home.'/drivers/tasklist_driver.php');
            	require_once($tsk->home.'/drivers/'.$n.'/'.$c.'.php');

	            $this->tsk_db = new $c($tsk);
    	        // -- END
        	}
        }

 		$n = 0;
 		if ($this->tsk_db) {
	        foreach ($this->tsk_db->get_lists(tasklist_driver::FILTER_PERSONAL | tasklist_driver::FILTER_WRITEABLE) as $a) {
	            if (!$a['active'])
	                continue;
	            $c = new html_checkbox([
	    				'name' 	    => '_syncgw_t'.$n,
		   			    'id' 	    => '_syncgw_t'.$n,
					    'value'     => self::TASK_FULL.$a['tasklist_id'],
	            ]);
	    	    $args['blocks']['syncgw_t']['options']['syncgw'.$n++] = [
		       			'title' 	=> rcube::Q('"'.$a['name'].'"'),
				     	'content' 	=> $c->show(strpos($prefs, self::TASK_FULL.$a['tasklist_id'].';') !== false ?
				     	                        self::TASK_FULL.$a['tasklist_id'] : null),
				];
	        }
 		}

        return $args;
    }

    /**
     * 	Save preferences
     *
	 *	@param 	array $args: section list
	 *	@return array $args
     */
	public function select_for_sync_save_pref($args) {

		if ($args['section'] != 'select_for_sync_pref')
		    return $args;

        $this->rc->config->get('syncgw');

        $prefs = '';

        for ($n=0; $n < self::MAX; $n++)
            if (isset($_POST['_syncgw_a'.$n]))
                $prefs .= $_POST['_syncgw_a'.$n].';'.$_POST['_syncgw_p'.$n].';';

        for ($n=0; $n < self::MAX; $n++)
            if (isset($_POST['_syncgw_c'.$n]))
                $prefs .= $_POST['_syncgw_c'.$n].';';

        for ($n=0; $n < self::MAX; $n++)
            if (isset($_POST['_syncgw_t'.$n]))
                $prefs .= $_POST['_syncgw_t'.$n].';';

   		$this->rc->user->save_prefs([ 'syncgw' => $prefs ]);

        return $args;
    }

}

?>
