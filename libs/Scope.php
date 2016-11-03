<?php

define("SCOPE_PARENT", 1);
define("SCOPE_CHILD", 2);

class Scope extends DatabaseConnection {
    
    public $join_statement = '';
    public $where_statement = '';
    public $models = array();
    
    function model(){
        return $this->models[0];
    }
    
    function __construct($cls) 
    {
        $this->models[0] = $cls;
    }
    
    public function where($query_array) {
        if($this->is_queried())
        {
            return $this->select_elements_with($query_array);
        }
        if(is_array($query_array))
        {
            $where = $this->where_statement;
            foreach($query_array as $unsafe_key => $unsafe_value)
            {
                $unsafe_table_col = explode('.', $unsafe_key);
                $unsafe_table = $this->model();
                if(count($unsafe_table_col) > 1)
                {
                    $unsafe_key = $unsafe_table_col[1];
                    $unsafe_table = $unsafe_table_col[0];
                }
                else {
                    $unsafe_key = $unsafe_table_col[0];
                }
                $unsafe_value_id = Model::get_pk_value($unsafe_value);
                $unsafe_key = Model::to_fk($unsafe_value, $unsafe_key);
                
                $where = empty($where) ? '' : $where . " AND";
                
                $safe_value = $this->check_str($unsafe_value_id);
                $safe_key = $this->check_str($unsafe_key);
                $safe_tabel = $this->check_str($unsafe_table);
                $where = $where . " `" . $safe_tabel ."DB`.`" . $safe_key . "` = '" . $safe_value . "'";
            }
            $this->where_statement = $where;
            return $this;
        }
        throw new Exception("Not a correct use of Scope.");
    }
    
    public function where_in($key, $values_array) 
    {
        if($this->is_queried())
        {
            return $this;
        }
        if(is_array($values_array) && !empty($key))
        {
            $where = empty($this->where_statement) ? '' : $this->where_statement . " AND";
            $safe_values = array();
            foreach($values_array as $unsafe_value)
            {
                array_push($safe_values, "'" . $this->check_str($unsafe_value). "'");
            }
            $safe_key = $this->check_str($key);
            $where = $where . " `" . $this->model() ."DB`.`" . $safe_key . "` IN (" . implode(",", $safe_values) . ")";
            
            $this->where_statement = $where;
            return $this;
        }
        return $this->none();
    }
    
    public function select_elements_with($properties) 
    {
        if(is_array($properties))
        {
            foreach($this->elements as $key => $element)
            {
                if(!Scope::has_element_properties($element, $properties))
                {
                    unset($this->elements[$key]);
                }
            }
        }
        return $this;
    }
    
    public function join($model, $on_statement = SCOPE_PARENT, $kind = "INNER")
    {
        $own_model = $this->model();
        if($on_statement == SCOPE_PARENT)
        {
            $this->join_statement = $this->join_statement . " " . $kind . " JOIN `" . $model . "DB`"
                                                          . " ON `" . $own_model . "`.`" . $model . "_id`"
                                                          . " = `" . $model . "DB`.`" . $model::get_pk() . "`";
        }
        elseif($on_statement == SCOPE_CHILD)
        {
            $this->join_statement = $this->join_statement . " " . $kind . " JOIN `" . $model . "DB`"
                                                          . " ON `" . $own_model . "DB`.`" . $own_model::get_pk() . "`"
                                                          . " = `" . $model . "DB`.`" . $own_model . "_id`";
        }
        elseif(is_string ($on_statement))
        {
            // model2.model3=model2.model3
            $part = explode('=', $on_statement);
            $part_a = explode('.',$part[0]);
            $part_b = explode('.',$part[1]);
            if(count($part_a) <= 1 || count($part_b) <= 1)
            {
                throw new Exception("Wrong use of join.");
            }
            $table_a = $this->check_str($part_a[0]);
            $table_b = $this->check_str($part_b[0]);
            $col_a = $this->check_str(Model::to_fk($part_a[1],$part_a[1]));
            $col_b = $this->check_str(Model::to_fk($part_b[1],$part_b[1]));
            $this->join_statement = $this->join_statement . " " . $kind . " JOIN `" . $model . "DB`"
                                                          . " ON `" . $table_a . "DB`.`" . $col_a . "`"
                                                          . " = `" . $table_b . "DB`.`" . $col_b . "`";
        }
        array_push($this->models, $model);
        return $this;
    }
    
    public function query($force = false)
    {
        if($this->is_queried())
        {
            return $this;
        }
        return $this->reload($force);
    }
    
    public function reload($force = false)
    {
        $this->elements = array();
        $response = $this->send_query($this->build_query());
        $error = $this->error();
        if($error && $force){
            throw new Exception($error);
        }
        $this->close_connection();
        if($response)
        {
            while($obj = mysqli_fetch_object($response, $this->model()))
            {
                $pk = $this->model()."id";
                $property = $obj::get_pk();
                $obj->$property = $obj->$pk;
                $this->elements[$obj->$property] = $obj;                
            }
        }
        return $this;
    }
    
    public function none()
    {
        $this->elements = array();
        return $this;
    }
    
    public function find_by($col, $value = '')
    { 
        if(is_array($col) && empty($value))
        {
            $properties = $col;
        }
        else {
            $properties = array($col => $value);
        }
        return $this->where($properties)->first();
    }
        
    public function find($value, $scope = false)
    {
        $model = $this->model();
        $pk = $model::get_pk();
        $properties = array($pk => $value);
        if($scope)
        {
            return $this->where($properties);
        }
        return $this->where($properties)->first();
    }
    
    public function exists()
    {
        $this->query();
        if(count($this->elements) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function first()
    {
        if($this->exists())
        {
            return array_values($this->elements)[0];
        }
        else {
            return NULL;
        }
    }    
    
    public function all()
    {
        $this->query();
        return $this->elements;
    }
    
    public function array_of_($property)
    {
        $this->query();
        $result = array();
        foreach(array_values($this->elements) as $object)
        {
            array_push($result, $object->$property);
        }
        return $result;
    }
    
    public function scope_of_its($model, $fk = '', $pk = 'id')
    {
        $ids = $this->array_of_($pk);
        if(empty($fk))
        {
            $fk = strtolower($this->model()) . '_id';
        }
        return $model::scope()->where_in($fk,$ids);
    }
    
    /**
     * This function adds a scope to every element of the scope it's called on.
     * If there is a model having manny children this function adds a scope to
     * every element to scope the element's children.
     * Use:
        $questions_scope = Question::scope();
        $questions = $questions_scope->has_manny_of('Answer');
        $questions[0]->answer_scope->all();
     * 
     * @param type $model
     * @param type $fk
     * @param type $pk
     * @return \Scope
     */
    public function has_manny_of($model, $fk = '', $pk = 'id') 
    {
        $this->query();
        $fk = empty($fk) ? strtolower($this->model()) . '_id' : $fk;
        
        if(is_a($model, 'Scope'))
        {
            $model_scope = $model->query();           
        }
        else
        {
            $model_scope = $this->scope_of_its($model, $fk, $pk)->query();
        }
        $property = strtolower($model_scope->model()).'_scope';
        foreach($this->elements as $object)
        {
            $element_models = clone $model_scope;
            $object->$property = $element_models->where(array($fk => $object->$pk));
        }
        return $this;
    }
    
    //private
    //=======
    
    private function is_queried()
    {
        return isset($this->elements) && is_array($this->elements);
    }

    private function build_query() 
    {
        if(empty($this->sql_query))
        {
            $col = '';
            foreach($this->models as $cls)
            {
                $col = empty($col) ? '' : $col . ',';
                $col = $col . " `" . $cls . "DB`.`" . $cls::get_pk() . "` as `". $cls . "id`, `{$cls}DB`.*";
            }
            $query = "SELECT " . $col . " FROM `" . $this->model() . "DB`";
            if(!empty($this->join_statement))
            {
                $query = $query . $this->join_statement;            
            }
            if(!empty($this->where_statement))
            {
                $query = $query . ' WHERE' . $this->where_statement;
            }
            $this->sql_query = $query.';';
        }
        return $this->sql_query;
    }
    
    //static
    //=======
    
    static function has_element_properties($element, $properties)
    {
        $fine_element = true;
        foreach($properties as $property => $search_value)
        {
            $value = Model::get_pk_value($search_value);
            $fk = Model::to_fk($search_value, $property);
            if($element->$fk != $value)
            {
                $fine_element = false;
            }
        }
        return $fine_element;
    }
}