<?php

    $xmlFile = file_get_contents("install/database.xml");

    $xml = new SimpleXMLElement($xmlFile);

    $type = (isset($_GET['type'])?$_GET['type']:'sql');

    echo '<style>body {font-family: arial;}</style>';

    foreach ($xml->table as $table) {
        
        if ($type == 'sql') {
        
            $before = 'CREATE TABLE `'.$table->attributes()['name'].'` (<br />';
            $after = '<br />);<br /><br />';
            
        } else if ($type = 'html') {
            
            $before = '<br /><br /><strong>'.$table->attributes()['name'].'</strong> - '.$table->info.'<br /><table border="1" width="100%">';
            $before .= '<tr><th width="200px">Column Name</th><th width="100px">Data Type</th><th>Description</th></tr>';
            $after = '</table>';
        
        }
        
        $cols = array();
        
        $primary = array();
        
        foreach ($table->columns->column as $column) {
        
            $attributes = $column->attributes();
            
            if ($attributes['primary'] == 'true') {
            
                $primary[] = $attributes['name'];
                
            }
            
        
            if ($type == 'sql') {
                $cols[] = '&nbsp;&nbsp;&nbsp;&nbsp;`'.$attributes['name'].'` '.$attributes['type'].(isset($attributes['length'])?'('.$attributes['length'].')':'');
            
        } else if ($type = 'html') {
            
            $cols[] = '<tr>
                <td>'.(($attributes['primary'] == 'true')?'<strong>'.$attributes['name'].'</strong>':$attributes['name']).'</td>
                <td>'.$attributes['type'].(isset($attributes['length'])?'('.$attributes['length'].')':'').'</td>
                <td>'.$column.'</td>
            </tr>';
        
        }
            
        }
        
        if (count($primary)>0 && $type == 'sql') {
        
            $cols[] = '&nbsp;&nbsp;&nbsp;&nbsp;PRIMARY KEY('.implode(', ', $primary).')';
            
        }
        
        if ($type == 'sql') {
            $columns = implode(', <br />', $cols);
        } else if ($type == 'html') {
            $columns = implode('', $cols);
        }
        
        echo $before.$columns.$after;
    
    }

?>