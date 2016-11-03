<?php

class Model extends DatabaseConnection {

    function __construct($properties = array()) {
        
        $this->write_properties($properties);
        return $this;
    }

    static function scope()
    {
        return new Scope(get_called_class());
    }
        
    function get_scope($model) {
        $scope = strtolower($model).'_scope';
        if(!isset($this->$scope)){
            $this->has_manny_of($model);
        }
        return $this;
    }
    
    function save($force = false) 
    {
        if($this->got_pk())
        {
            //TODO update function needed
        }
        if(method_exists($this, "validate"))
        {
            $this->validate();
        }
        $cols = '';
        $vals = '';
        foreach(get_object_vars($this) as $unsave_key => $unsave_value)
        {
            if(is_array($unsave_value) || is_a($unsave_value, 'Scope'))
            {
                continue;
            }
            $unsave_value_id = Model::get_pk_value($unsave_value);
            $unsave_key = Model::to_fk($unsave_value, $unsave_key);
            
            $value = $this->check_str($unsave_value_id);
            $key = $this->check_str($unsave_key);
            if(!is_numeric($value))
            {
                $value = "'{$value}'";
            }
            $cols = empty($cols) ? "`{$key}`" : "{$cols},`{$key}`";
            $vals = empty($vals) ? $value : $vals . "," . $value;            
        }
        if($cols == '' || $vals == '')
        {
            throw new Exception("Empty object cannot be saved.");
        }
        $cls = get_called_class();
        $this->send_query("INSERT INTO `{$cls}DB` ({$cols}) VALUES({$vals});");
        $ex = $this->error();
        if(!empty($ex) && $force === true)
        {
            throw new Exception($ex);
        }
        $id = $this->inserted_id();
        $this->close_connection();
        $this->set_pk_value($id);
        return $this;
    }
    
    
    function delete($force = false) 
    {
        $unsave_value_id = Model::get_pk_value($this);
        $unsave_key = Model::get_pk($this);

        $value = $this->check_str($unsave_value_id);
        $key = $this->check_str($unsave_key);
        if(!is_numeric($value))
        {
            $value = "'{$value}'";
        }
        $col =  "`{$key}`";
        $val =  $value;            
        
        $cls = get_called_class();
        $this->send_query("DELETE FROM `{$cls}DB` WHERE {$col} = {$val};");
        $ex = $this->error();
        if(!empty($ex) && $force === true)
        {
            throw new Exception($ex);
        }
        $this->close_connection();
        return;
    }
    
    function has_manny_of($model, $fk = '', $pk = 'id') 
    {
        if(empty($fk))
        {
            $fk = strtolower(get_called_class()) . '_id';
        }
        $property = strtolower($model).'_scope';
        $this->$property = $model::scope()->where(array($fk => $this->$pk));
        return $this;
    }
    
    
    
    function write_properties($properties = [], $priority = true) {
        foreach($properties as $key => $value)
        {
            if(is_int($key))
            {
                throw new InvalidArgumentException('Function: write_properties(), Argument: $properties');
            }
            $old_key = $key;
            if(is_a($value, "Model"))
            {
                $old_key = $key.'_id';
            }
            else 
            {
                $old_key = str_replace('_id', '', $key);
            }
            
            if($priority === true || !(isset($this->$key) && isset($this->$old_key)))
            {                    
                $this->$key = $value;
                if($old_key != $key && isset($this->$old_key))
                {
                    unset($this->$old_key);
                }
            }
        }
        return $this;
    }
    
    function validate_existence($property) {
        if(!isset($this->$property) || $this->$property == '' || $this->$property == NULL)
        {
            $property_id = $property.'_id';
            if(!isset($this->$property_id) || $this->$property_id == '' || $this->$property_id == NULL)
            {
                $model = get_class($this);
                Session::add('msg', i18n::get('property_missing', 
                                         array('property' => "{$model}.{$property}")));
                throw new Exception("Not valid.");
            }
        }
        return true;
    }
    
    static function get_pk_value($model) 
    {
        if(is_a($model, "Model"))
        {
            $pk = $model::get_pk();
            return $model->$pk;
        }
        return $model;
    }
    
    function set_pk_value($val) 
    {
        $pk = $this::get_pk();
        $this->$pk = $val;
    }
    
    function got_pk() 
    {
        $pk = $this::get_pk();
        return isset($this->$pk);
    }
    
    static function get_pk() 
    {
        return 'id';
    }
    
    static function to_fk($argument, $alternativ = NULL) {
        if(is_a($argument, "Model"))
        {
            return strtolower(get_class($argument)).'_id';
        }
        if(is_string($argument))
        {
            $model_cls =  ucfirst($argument);
            if(class_exists($model_cls) && is_a(new $model_cls(), "Model"))
            {
                return $argument.'_id';
            }
        }
        if($alternativ !== NULL)
        {
            return $alternativ;
        }
        return $argument;
    }
    
    function get_parent($parent_model) {
        
        if(isset($this->$parent_model) && is_a($this->$parent_model, "Model"))
        {
            return $this->$parent_model;
        }
        elseif(isset($this->{$parent_model.'_id'}))
        {
            $parent_model_class = ucfirst($parent_model);
            $scope = $parent_model_class::scope()->find($this->{$parent_model.'_id'}, true);
            if($scope->exists())
            {
                $this->$parent_model = $scope->first();
                unset($this->{$parent_model.'_id'});
                return $this->$parent_model;
            }
        }
        throw new Exception("No such parent Exception.");
    }
}