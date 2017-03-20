<?php namespace App\Registry;

class Registry
{
    /**
     * Mapping takes folders and interfaces to search for
     *
     * @param  array   $interfaces The array of interfaces
     * @param  array   $folders    The array of folders to search
     * @return array
     */
    public function map($interfaces, $folders)
    {
        $mapping = [];
        foreach ($folders as $folder) {
            foreach (glob(app()->path() . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . '*.php') as $file) {
                $contents = file_get_contents($file);

                preg_match_all('/(?<=implements).+?(?={)/s', $contents, $matches);

                if (!empty($matches[0])) {
                    $matches[0] = preg_replace('/[\r\n]+/', '', $matches[0]);

                    foreach ($matches[0] as $classes) {
                        $classes        = preg_replace('/\s/', '', $classes);
                        $set_interfaces = explode(',', $classes);

                        foreach ($interfaces as $interface) {
                            if (in_array($interface, $set_interfaces)) {
                                preg_match('/(?<=namespace ).+?(?=;)/', $contents, $namespace);
                                preg_match('/(?<=class ).+?(?=\s)/', $contents, $classname);
                                $mapping[$interface][] = $namespace[0] . '\\' . $classname[0];
                            }
                        }
                    }
                }
            }
        }

        return $mapping;
    }
}
