<?php namespace HannesD;

class Store2File {
    //File storage path
    private $fileStorage;

    /**
     * Store2File constructor.
     * @param $fileStorage Path to file storage
     */
    public function __construct($fileStorage) {
        $this->fileStorage = $fileStorage;
    }

    /**
     * Read from file storage
     *
     * @return array
     */
    private function read() {
        //List to return
        $data = [];
        //Get each row of file from storage
        foreach(file($this->fileStorage) as $row) {
            //Explode with ;
            $data[] = explode(';', trim($row));
        }

        //Return data
        return $data;
    }

    /**
     * Write data array to file storage
     * @param $data
     */
    private function write($data) {
        //Implode each key of $data with new line and implodes each array in $data with ;
        file_put_contents($this->fileStorage, implode("\n", array_map(function($item) { return implode(';', $item); }, $data)));
    }

    /**
     * Find specific item in file storage using name
     *
     * @param $name
     * @return array
     */
    private function find($name) {
        //Get all data
        $data = $this->read();
        //Loop through each item in dataset
        foreach($data as $index => $item) {
            //If first value (index 0) of item in dataset equals $name. Return it with index
            if($item[0] == $name) {
                return [
                    'index' => $index,
                    'value' => $item
                ];
            }
        }

        //No item was found, return with index -1 and value set to false
        return [
            'index' => -1,
            'value' => false
        ];
    }

    /**
     * Get specific item from dataset. Public for use by external controller
     * Will either get all or just one depending on $name
     *
     * @param bool $name
     * @return array
     */
    public function get($name = false) {
        //If name is false (not provided) return all records from file storage
        if($name === false) { return $this->read(); }

        //return value from find-function with name provided
        return $this->find($name);
    }

    /**
     * Creates new record for file storage
     * @param array $values
     */
    public function create($values = []) {
        //Get all records from file storage
        $data = $this->read();

        //Add $values to $data
        $data[] = $values;

        //Write $data to file
        $this->write($data);
    }

    /**
     * Update an existing recordset using name as reference
     *
     * @param $name
     * @param array $values
     * @return bool
     */
    public function update($name, $values = []) {
        //Find record from file storage
        $item = $this->find($name);
        //If index equals -1 record was not found, return false
        if($item['index'] == -1) { return false; }

        //Get all records
        $data = $this->read();
        //Update found index with new values
        $data[$item['index']] = $values;
        //write to file
        $this->write($data);
    }

    /**
     * Delete record from file storage
     *
     * @param $name
     * @return bool
     */
    public function delete($name) {
        //Find record from file storage
        $item = $this->find($name);
        //If index equals -1 record was not found, return false
        if($item['index'] == -1) { return false; }

        //Get all records
        $data = $this->read();
        //Remove found item from $data
        array_splice($data, $item['index'], 1);
        //Write to file
        $this->write($data);
    }
}