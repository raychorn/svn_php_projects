<? php
$__cache_files__ = array();
$__cache_dirs__ = array();

$classesDir = array (
    'lib'
);
function scandir_through($dir,$ftype) {
  global $__cache_files__;
  global $__cache_dirs__;
  $files = array();
  $directories = new RecursiveIteratorIterator(
      new ParentIterator(new RecursiveDirectoryIterator($dir)),
      RecursiveIteratorIterator::SELF_FIRST);

  $count_dirs = 0;
  $count_files = 0;
  foreach ($directories as $directory) {
      $dirname = $directory.'/*'.((strlen($ftype) > 0) ? '.'.$ftype : '');
      if (!array_key_exists($directory.'', $__cache_dirs__)) {
        //echo('scandir_through.3 --> '.$directory.'<BR/>');
        $__cache_dirs__[$directory.''] = $count_dirs;
        ++$count_dirs;
        foreach (glob($dirname) as $filename) {
          if (!array_key_exists($filename, $__cache_files__)) {
            //echo('scandir_through.4 --> '.$filename.'<BR/>');
            $__cache_files__[$filename] = $count_files;
            ++$count_files;
          }
        }
      }
  }

  echo('scandir_through.5 --> count_dirs='.$count_dirs.'<BR/>');
  echo('scandir_through.6 --> count_files='.$count_files.'<BR/>');

  $files = array_keys($__cache_files__);
  sort($files);
  echo('1.1 --> '.var_dump($files).'<BR/>');

  return $files;
}

function __autoload($args) {
    global $classesDir;
    foreach ($classesDir as $directory) {
      //echo('1 --> '.$directory.'<BR/>');
      $files = scandir_through($directory,'php');
      //echo('1.1 --> '.var_dump($files).'<BR/>');
      foreach ($files as $filename) {
        $fname = $filename;
        echo('2 --> '.$fname.'<BR/>');
        if ( (is_file($fname)) && (file_exists($fname)) ) {
          echo('3 --> '.$fname.'<BR/>');
          require_once ($fname);
        }
      }
    }
}

//__autoload(1);

?>
