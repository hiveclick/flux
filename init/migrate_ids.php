<?php
use Mojavi\Util\StringTools;

try {
    set_time_limit(0);
    require_once(dirname(__FILE__) . '/lib/Connection.php');
    require_once(dirname(__FILE__) . '/../admin/webapp/config.php');
    require_once(dirname(__FILE__) . '/../admin/webapp/lib/Mojavi/mojavi.php');

    \Mojavi\Controller\Controller::newInstance('\Mojavi\Controller\BasicConsoleController');
    \Mojavi\Controller\Controller::getInstance()->loadContext();

    // Setup Vars
    $init_dir = dirname(__FILE__) . "/";
    $base_dir = $init_dir . "../admin/";
    $docroot_dir = $base_dir . "docroot/";
    $webapp_dir = $base_dir . "webapp/";
    $meta_dir = $webapp_dir . "meta/";
    $cache_dir = $webapp_dir . "cache/";
    $config_dir = $webapp_dir . "config/";
    
    Connection::getInstance()->loadDatabasesFromFile($config_dir . '/databases.ini');
    
    echo StringTools::consoleColor('Flux ID Migration Script', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    echo StringTools::consoleColor(str_repeat('=', 50), StringTools::CONSOLE_COLOR_GREEN) . "\n";
    
    // Now rename the collections
    /*
    $rename_collections = array(
        'flux_admin.client' => 'flux_admin.client_new',
        'flux_admin.daemon' => 'flux_admin.daemon_new',
        'flux_admin.data_field' => 'flux_admin.data_field_new',
        'flux_admin.domain_group' => 'flux_admin.domain_group_new',
        'flux_admin.fulfillment' => 'flux_admin.fulfillment_new',
        'flux_admin.offer' => 'flux_admin.offer_new',
        'flux_admin.offer_page' => 'flux_admin.offer_page_new',
        'flux_admin.saved_search' => 'flux_admin.saved_search_new',
        'flux_admin.server' => 'flux_admin.server_new',
        'flux_admin.split' => 'flux_admin.split_new',
        'flux_admin.traffic_source' => 'flux_admin.traffic_source_new',
        'flux_admin.user' => 'flux_admin.user_new',
        'flux_admin.vertical' => 'flux_admin.vertical_new',
        'flux_admin.client_old' => 'flux_admin.client',
        'flux_admin.daemon_old' => 'flux_admin.daemon',
        'flux_admin.data_field_old' => 'flux_admin.data_field',
        'flux_admin.domain_group_old' => 'flux_admin.domain_group',
        'flux_admin.fulfillment_old' => 'flux_admin.fulfillment',
        'flux_admin.offer_old' => 'flux_admin.offer',
        'flux_admin.offer_page_old' => 'flux_admin.offer_page',
        'flux_admin.saved_search_old' => 'flux_admin.saved_search',
        'flux_admin.server_old' => 'flux_admin.server',
        'flux_admin.split_old' => 'flux_admin.split',
        'flux_admin.traffic_source_old' => 'flux_admin.traffic_source',
        'flux_admin.user_old' => 'flux_admin.user',
        'flux_admin.vertical_old' => 'flux_admin.vertical',
        'flux_admin.campaign' => 'flux_admin.campaign_new',
        'flux_admin.campaign_old' => 'flux_admin.campaign',
        'flux_lead.lead' => 'flux_lead.lead_new',
        'flux_lead.lead_old' => 'flux_lead.lead',
        'flux_admin.split_queue' => 'flux_admin.split_queue_new',
        'flux_admin.split_queue_old' => 'flux_admin.split_queue',
    );
    $admin_db = Connection::getInstance()->getDbConnection("madmin");
    foreach ($rename_collections as $key => $coll_name) {
        echo "Renaming collection " . $key . " to " . $coll_name . "...\n";
        $res = $admin_db->command(array(
            "renameCollection" => $key,
            "to" => $coll_name
        ));
    }
    */
    
    
    $id_mapping = array('client' => array(), 
                        'user' => array(), 
                        'vertical' => array(), 
                        'traffic_source' => array(), 
                        'daemon' => array(),
                        'server' => array(),
                        'data_field' => array(),
                        'domain_group' => array(),
                        'fulfillment' => array(),
                        'split' => array(),
                        'offer' => array(),
                        'offer_page' => array(),
                        'campaign' => array(),
                        'saved_search' => array()
    );
    
    echo StringTools::consoleColor('Migrating clients', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->client;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->client_new;
    $new_collection->drop();
    $clients = $old_collection->find();
    while ($clients->hasNext()) {
        $client = $clients->next();
        if (isset($client['_id'])) {
            if (!\MongoId::isValid($client['_id'])) {
                $old_id = $client['_id'];
                echo "Migrating client #" . $old_id . "\n";     
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($client['_id']);
                $new_collection->save($client);
                if (isset($client['_id'])) {
                    $new_id = $client['_id'];
                    $id_mapping['client'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($client, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating users', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->user;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->user_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating user #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
                if (isset($id_mapping['client'][(string)$record['client']['client_id']])) {
                    $record['client']['client_id'] = new \MongoId($id_mapping['client'][(string)$record['client']['client_id']]);
                }
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['user'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating daemons', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->daemon;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->daemon_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating daemon #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
                
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['daemon'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating domain groups', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->domain_group;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->domain_group_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating domain group #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['domain_group'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating servers', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->server;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->server_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating server #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['server'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating data field', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->data_field;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->data_field_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating data field #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['data_field'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating traffic source', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->traffic_source;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->traffic_source_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating traffic source #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['traffic_source'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating vertical', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->vertical;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->vertical_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating vertical #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['vertical'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating fulfillment', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->fulfillment;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->fulfillment_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating fulfillment #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
                array_walk($record['mapping'], function(&$value) use($id_mapping) {
                    echo " - Migrating data field #" . $value['data_field']['data_field_id'] . "\n";
                    if (isset($id_mapping['data_field'][(string)$value['data_field']['data_field_id']])) {
                        $value['data_field']['data_field_id'] = new \MongoId($id_mapping['data_field'][(string)$value['data_field']['data_field_id']]);
                    }
                });
                if (isset($id_mapping['client'][(string)$record['client']['client_id']])) {
                    $record['client']['client_id'] = new \MongoId($id_mapping['client'][(string)$record['client']['client_id']]);
                } else {
                    throw new \Exception('Cannot find client id ' . $record['client']['client_id'] . ' for fulfillment #' . $old_id);
                }
                
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['fulfillment'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating offer', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->offer;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->offer_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating offer #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
                if (isset($record['events']) && is_array($record['events'])) {
                    array_walk($record['events'], function(&$value) use($id_mapping) {
                        echo " - Migrating event #" . $value['event_id'] . "\n";
                        if (isset($id_mapping['data_field'][(string)$value['event_id']])) {
                            $value['event_id'] = new \MongoId($id_mapping['data_field'][(string)$value['event_id']]);
                        }
                    });
                }
                if (isset($id_mapping['vertical'][(string)$record['vertical']['vertical_id']])) {
                    $record['vertical']['vertical_id'] = new \MongoId($id_mapping['vertical'][(string)$record['vertical']['vertical_id']]);
                } else {
                    throw new \Exception('Cannot find vertical id ' . $record['vertical']['vertical_id'] . ' for offer #' . $old_id);
                }
                if (isset($id_mapping['client'][(string)$record['client']['client_id']])) {
                    $record['client_id'] = new \MongoId($id_mapping['client'][(string)$record['client']['client_id']]);
                    $record['client']['client_id'] = new \MongoId($id_mapping['client'][(string)$record['client']['client_id']]);
                } else {
                    throw new \Exception('Cannot find client id ' . $record['client']['client_id'] . ' for offer #' . $old_id);
                }
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['offer'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating offer page', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->offer_page;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->offer_page_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating offer_page #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
                if (isset($record['offer']) && isset($id_mapping['offer'][(string)$record['offer']['offer_id']])) {
                    $record['offer']['offer_id'] = new \MongoId($id_mapping['offer'][(string)$record['offer']['offer_id']]);
                    $new_collection->save($record);
                    if (isset($record['_id'])) {
                        $new_id = $record['_id'];
                        $id_mapping['offer_page'][(string)$old_id] = (string)$new_id;
                    } else {
                        throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                    }
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating split', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->split;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->split_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating split #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
                array_walk($record['offers'], function(&$value) use($id_mapping) {
                    echo " - Migrating offer #" . $value['offer_id'] . "\n";
                    if (isset($id_mapping['offer'][(string)$value['offer_id']])) {
                        $value['offer_id'] = new \MongoId($id_mapping['offer'][(string)$value['offer_id']]);
                    }
                });
                if (is_array($record['filters'])) {
                    array_walk($record['filters'], function(&$value) use($id_mapping) {
                        echo " - Migrating filter #" . $value['data_field_id'] . "\n";
                        if (isset($id_mapping['data_field'][(string)$value['data_field_id']])) {
                            $value['data_field_id'] = new \MongoId($id_mapping['data_field'][(string)$value['data_field_id']]);
                        }
                    });
                }
                if (isset($record['validators']) && is_array($record['validators'])) {
                    array_walk($record['validators'], function(&$value) use($id_mapping) {
                        echo " - Migrating filter #" . $value['data_field_id'] . "\n";
                        if (isset($id_mapping['data_field'][(string)$value['data_field_id']])) {
                            $value['data_field_id'] = new \MongoId($id_mapping['data_field'][(string)$value['data_field_id']]);
                        }
                    });
                }
                if (isset($id_mapping['fulfillment'][(string)$record['fulfillment']['fulfillment_id']])) {
                    $record['fulfillment']['fulfillment_id'] = new \MongoId($id_mapping['fulfillment'][(string)$record['fulfillment']['fulfillment_id']]);
                }
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['split'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating campaign', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->campaign;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->campaign_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating campaign #" . (string)$old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                if (!\MongoId::isValid($record['offer']['offer_id']) && isset($id_mapping['offer'][(string)$record['offer']['offer_id']])) {
                    $record['offer']['offer_id'] = new \MongoId($id_mapping['offer'][(string)$record['offer']['offer_id']]);
                }
                if (!\MongoId::isValid($record['client']['client_id']) && isset($id_mapping['client'][(string)$record['client']['client_id']])) {
                    $record['client']['client_id'] = new \MongoId($id_mapping['client'][(string)$record['client']['client_id']]);
                }
                if (!\MongoId::isValid($record['traffic_source']['traffic_source_id']) && isset($id_mapping['traffic_source'][(string)$record['traffic_source']['traffic_source_id']])) {
                    $record['traffic_source']['traffic_source_id'] = new \MongoId($id_mapping['traffic_source'][(string)$record['traffic_source']['traffic_source_id']]);
                }
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['campaign'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating saved search', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("admin")->saved_search;
    $new_collection = Connection::getInstance()->getDbConnection("admin")->saved_search_new;
    $new_collection->drop();
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $record = $records->next();
        if (isset($record['_id'])) {
            if (!\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                echo "Migrating saved search #" . $old_id . "\n";
                //$ret_val = $old_collection->remove(array('_id' => $old_id));
                unset($record['_id']);
    
                $new_collection->save($record);
                if (isset($record['_id'])) {
                    $new_id = $record['_id'];
                    $id_mapping['saved_search'][(string)$old_id] = (string)$new_id;
                } else {
                    throw new \Exception('Cannot find _id in insert ret_val: ' . var_export($record, true));
                }
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating leads', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("lead")->lead;
    $new_collection = Connection::getInstance()->getDbConnection("lead")->lead_new;
    $new_collection->drop();
    $counter = 0;
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $counter++;
        if ($counter % 1000 == 0) { echo "Migrating leads " . $counter . "...\n"; }
        $record = $records->next();
        if (isset($record['_id'])) {
            if (\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                if (isset($record['_t']['client']) && isset($id_mapping['client'][$record['_t']['client']['client_id']])) {
                    $record['_t']['client']['client_id'] = new \MongoId($id_mapping['client'][$record['_t']['client']['client_id']]);
                }
                if (isset($record['_t']['offer']) && isset($id_mapping['offer'][$record['_t']['offer']['offer_id']])) {
                    $record['_t']['offer']['offer_id'] = new \MongoId($id_mapping['offer'][$record['_t']['offer']['offer_id']]);
                }
                if (isset($record['_e']) && is_array($record['_e'])) {
                    array_walk($record['_e'], function(&$value) use($id_mapping) {
                        if (isset($id_mapping['data_field'][(string)$value['data_field']['data_field_id']])) {
                            $value['data_field']['data_field_id'] = new \MongoId($id_mapping['data_field'][(string)$value['data_field']['data_field_id']]);
                        }
                        if (isset($id_mapping['offer'][(string)$value['offer']['offer_id']])) {
                            $value['offer']['offer_id'] = new \MongoId($id_mapping['offer'][(string)$value['offer']['offer_id']]);
                        }
                        if (isset($id_mapping['client'][(string)$value['client']['client_id']])) {
                            $value['client']['client_id'] = new \MongoId($id_mapping['client'][(string)$value['client']['client_id']]);
                        }
                    });
                }
    
                $new_collection->save($record);
            }
        }
    }
    
    echo StringTools::consoleColor('Migrating split queue', StringTools::CONSOLE_COLOR_GREEN) . "\n";
    $old_collection = Connection::getInstance()->getDbConnection("queue")->split_queue;
    $new_collection = Connection::getInstance()->getDbConnection("queue")->split_queue_new;
    $new_collection->drop();
    $counter = 0;
    $records = $old_collection->find();
    while ($records->hasNext()) {
        $counter++;
        if ($counter % 100 == 0) { echo "Migrating split queue " . $counter . "...\n"; }
        $record = $records->next();
        if (isset($record['_id'])) {
            if (\MongoId::isValid($record['_id'])) {
                $old_id = $record['_id'];
                if (isset($id_mapping['split'][$record['split']['split_id']])) {
                    $record['split']['split_id'] = new \MongoId($id_mapping['split'][$record['split']['split_id']]);
                }
                if (isset($id_mapping['offer'][$record['lead']['offer']['offer_id']])) {
                    $record['lead']['offer']['offer_id'] = new \MongoId($id_mapping['offer'][$record['lead']['offer']['offer_id']]);
                }
                if (isset($id_mapping['client'][$record['lead']['client']['client_id']])) {
                    $record['lead']['client']['client_id'] = new \MongoId($id_mapping['client'][$record['lead']['client']['client_id']]);
                }
                array_walk($record['attempts'], function(&$value) use($id_mapping) {
                    if (isset($id_mapping['fulfillment'][(string)$value['fulfillment']['fulfillment_id']])) {
                        $value['fulfillment']['fulfillment_id'] = new \MongoId($id_mapping['fulfillment'][(string)$value['fulfillment']['fulfillment_id']]);
                    }
                    if (isset($value['lead']['offer']['offer_id']) && isset($id_mapping['offer'][(string)$value['lead']['offer']['offer_id']])) {
                        $value['lead']['offer']['offer_id'] = new \MongoId($id_mapping['offer'][(string)$value['lead']['offer']['offer_id']]);
                    }
                    if (isset($value['lead']['client']['client_id']) && isset($id_mapping['client'][(string)$value['lead']['client']['client_id']])) {
                        $value['lead']['client']['client_id'] = new \MongoId($id_mapping['client'][(string)$value['lead']['client']['client_id']]);
                    }
                });
    
                $new_collection->save($record);
            }
        }
    }

    // Now rename the collections
    $rename_collections = array(
        'flux_admin.client' => 'flux_admin.client_old',
        'flux_admin.campaign' => 'flux_admin.campaign_old',
        'flux_admin.daemon' => 'flux_admin.daemon_old',
        'flux_admin.data_field' => 'flux_admin.data_field_old',
        'flux_admin.domain_group' => 'flux_admin.domain_group_old',
        'flux_admin.fulfillment' => 'flux_admin.fulfillment_old',
        'flux_admin.offer' => 'flux_admin.offer_old',
        'flux_admin.offer_page' => 'flux_admin.offer_page_old',
        'flux_admin.saved_search' => 'flux_admin.saved_search_old',
        'flux_admin.server' => 'flux_admin.server_old',
        'flux_admin.split' => 'flux_admin.split_old',
        'flux_admin.traffic_source' => 'flux_admin.traffic_source_old',
        'flux_admin.user' => 'flux_admin.user_old',
        'flux_admin.vertical' => 'flux_admin.vertical_old',
        'flux_admin.client_new' => 'flux_admin.client',
        'flux_admin.campaign_new' => 'flux_admin.campaign',
        'flux_admin.daemon_new' => 'flux_admin.daemon',
        'flux_admin.data_field_new' => 'flux_admin.data_field',
        'flux_admin.domain_group_new' => 'flux_admin.domain_group',
        'flux_admin.fulfillment_new' => 'flux_admin.fulfillment',
        'flux_admin.offer_new' => 'flux_admin.offer',
        'flux_admin.offer_page_new' => 'flux_admin.offer_page',
        'flux_admin.saved_search_new' => 'flux_admin.saved_search',
        'flux_admin.server_new' => 'flux_admin.server',
        'flux_admin.split_new' => 'flux_admin.split',
        'flux_admin.traffic_source_new' => 'flux_admin.traffic_source',
        'flux_admin.user_new' => 'flux_admin.user',
        'flux_admin.vertical_new' => 'flux_admin.vertical',
        'flux_lead.lead' => 'flux_lead.lead_old',
        'flux_lead.lead_new' => 'flux_lead.lead',
        'flux_admin.split_queue' => 'flux_admin.split_queue_old',
        'flux_admin.split_queue_new' => 'flux_admin.split_queue',
    );
    $admin_db = Connection::getInstance()->getDbConnection("madmin");
    foreach ($rename_collections as $key => $coll_name) {
        echo "Renaming collection " . $key . " to " . $coll_name . "...\n";
        $res = $admin_db->command(array(
            "renameCollection" => $key,
            "to" => $coll_name
        ));
    }
    
} catch (\Exception $e) {
    echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
}
echo "Done\n";