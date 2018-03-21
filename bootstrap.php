<?php


class Ps4Autoloader
{

    protected $prefixes = array();


    //prefix ir klases failo keliasi masyva
    public function add($prefix, $base_dir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';

        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }

        return $this;
    }


    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }


    public function loadClass($class)
    {
        $prefix = $class;

        //leidziam klases faila
        while (false !== $pos = strrpos($prefix, '\\')) {

            // gaunam prefix
            $prefix = substr($class, 0, $pos + 1);

            $relative_class = substr($class, $pos + 1);

            // bandom paleisti klases faila
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    //paleidziam klases faila
    protected function loadMappedFile($prefix, $relative_class)
    {

        //ieskom namespace prefix
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $base_dir) {

            // generuojam pilna direktorija i faila
            $file  = $base_dir;
            $file .= str_replace('\\', '/', $relative_class);
            $file .= '.php';

            if (file_exists($file)) {
                require $file;
                return $file;
            }
        }

        return false;
    }

}



$autoloader = new Ps4Autoloader;
$autoloader
    ->add('Nfq\\Academy\\Homework\\', __DIR__.'/src/')
    ->add('Acme\\', __DIR__.'/vendor/acme/')
    ->register();
