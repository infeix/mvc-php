<?php
/**
 * The name of a factory must be the same as the Model with the keyword 
 * 'Factory' at the end!
 * Example name for an User Model:
    UserFactory
 *  
 * In the constructor of a factory the default_properties can be assigned.
 * Use:
    public function __construct() {
        $this->default_properties = array(
            'answer' => Factory::build('Answer'),
            'user' => Factory::build('User'),
        );
    }
 * 
 * Every function can have an after_factories_build function. The build Model
 * is found in the property 'creation'.
 * Use:    
    public function after_factories_build() {
        $this->creation->question = $this->creation->answer->question;
    }
 * 
 * With a factory you can create fast and simple sample-inserts for your
 * database.
 * Use:
    $fresh_object = Factory::create('User');
 * 
 */
class Factory extends Model {
    
    public function __construct($model, $properties = [], $trait = '') {
        $this->creation = new $model();
        $this->model = $model;
        $this->properties = $properties;
        $this->trait = $trait;
    }
    
    static function create($model, $properties = [], $trait = '')
    {
        if(is_a($model, 'Factory'))
        {
            $factory = $model;
            $model = $factory->model;
            $properties = $factory->properties;
            $trait = $factory->trait;
        }
        $factory_cls = "{$model}Factory";
        require_once "test/factories/{$factory_cls}.php";
        $factory = new $factory_cls();
        if(!empty($trait))
        {
            $factory->$trait();
        }
        $factory->creation = new $model();
        $factory->creation->write_properties($factory->default_properties);
        $factory->creation->write_properties($properties);
        $factory->creation = Factory::create_all($factory->creation);
        if(method_exists($factory, 'after_factories_build')){
            $factory->after_factories_build();
        }
        $factory->creation->save(true);
        return $factory->creation;
    }
    
    static function build($model, $properties = array(), $trait = '')
    {
        return new Factory($model, $properties, $trait);
    }
    
    static function create_all($model)
    {
        foreach(get_object_vars($model) as $key => $value)
        {
            if(is_a($value, 'Factory'))
            {
                $model->$key = Factory::create($value);
            }
        }
        return $model;
    }
    
    static function generate_random_string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function generate_random_mail() {
        return Factory::generate_random_string(8).'@' . Factory::generate_random_string(5) . '.' . Factory::generate_random_string(3);
    }
    
    static function clean_database() {
        (new DatabaseSetup())->clean();
    }
    
    static function get_one_of($values)
    {
        return $values[rand(0, count($values)-1)];
    }
    
    static function generate_random_number($min = 1, $max = 9999)
    {
        return rand($min, $max);
    }  
    
    static function delete_all_of($model)
    {
        foreach($model::scope()->all() as $value)
        {
            $value->delete();
        }
    }  
}
