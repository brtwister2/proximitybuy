<?php

define('LINK',  'http://138.197.118.32/proximitybuy/dashboard/api/imgs/');

class Upload {

    public $useTable = false;

    public $name = 'Image';

    public $validate = array(
        'upload' => array(
            'rule' => array(
                'validFile', array(
                    'required' => true,
                    'extensions' => array(
                    )
                )
            )
        )
    );


    public function verificaMime64Valido ($tipo) {
        $tiposValidos = array ('jpg', 'jpeg', 'gif','png');

        foreach ($tiposValidos as $key => $tipoValido) {

            if ($tipoValido == $tipo) {
                return true;
            }
        }
        return false;
    }

    public function getImageType($binary){
        $types = array('jpeg' => "\xFF\xD8\xFF", 'gif' => 'GIF', 'png' => "\x89\x50\x4e\x47\x0d\x0a", 'bmp' => 'BM', 'psd' => '8BPS', 'swf' => 'FWS');

        foreach ($types as $type => $header) 
        {
            if (strpos($binary, $header) === 0) 
            {
                return $type;
            }
        }
        return false;
    }

    public function getBinaryString ($data) {

        $binary = explode(',', $data);

        return $binary[1];

    }

    public function uploadImage($base64img) {

        //$base64img = "data:image/png;base64,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";

        if ($base64img == null) {
            return false;
        }


        $data = base64_decode($base64img);

        $type = $this->getImageType($data);

        if ($type){
            $name =  uniqid() . '.' . $type;
            $file = "imgs/" . $name;
            $salvo = file_put_contents($file, $data);

            if ($salvo){
                return  LINK.$name;
            }
        }

        return false;
    }

    public function upload($data = null) {

        if (empty($this->data) || !$this->validates()) {
            return false;
        }

        $targetDir = new Folder();

        $targetDir->create(UPLOADS_AVATAR . AppAuth::user('id') . '_' . Security::hash(AppAuth::user('id')) . DS);

        if (!move_uploaded_file($this->data['Upload']['upload']['tmp_name'], UPLOADS_AVATAR . AppAuth::user('id') . '_' . Security::hash(AppAuth::user('id')) . DS . $this->data['Upload']['upload']['name'])) {
            return false;
        }

        return true;
    }

    public function delete($fileName = null, $cascade = true)
    {
        if ($fileName == null || trim($fileName) === '')
        {
            return;
        }

        $filePath = UPLOADS_AVATAR . AppAuth::user('id') . '_' . Security::hash(AppAuth::user('id')) . DS . $fileName;
        $file = new File($filePath);

        $success = $file->delete();

        return $success;
    }

    public function validFile($check, $settings)
    {
        $_default = array(
            'required' => false,
            'extensions' => array()
        );
        $settings['extensions'] = $this->validate['upload']['rule']['validFile']['extensions'];

        $_settings = array_merge($_default, (is_array($settings) ? $settings : array()));

        // Get the inner file array
        $_check = array_shift($check);

        if ($_settings['required'] == false && $_check['size'] == 0)
        {
            return true;
        }

        // No file passed
        if ($_settings['required'] && $_check['size'] == 0)
        {
            return false;
        }

        // PHP generated errors
        if ($_check['error'] !== 0)
        {
            return false;
        }

        // PHP-internal upload-check
        if (is_uploaded_file($_check['tmp_name']) == false)
        {
            return false;
        }

        // Validate file size
        if (!Validation::fileSize($_check, '<', AppConfig::read('System.uploads_filesize') . 'M'))
        {
            return __('The file is too large.');
        }

        // Validate extension 
        return Validation::extension($_check, $_settings['extensions']);
    }

}
