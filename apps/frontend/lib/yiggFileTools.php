<?php

/**
 * This is a library for functions that are related to the filesystem and maniplating files.
 *
 * @package     yigg
 * @subpackage  helpers
 */
class yiggFileTools extends sfFilesystem
{
  /**
   * Creates a new instance of yiggFileTools
   *
   * @param sfEventDispatcher A sfEventDispatcher instance
   * @param sfFormatter A sfFormatter instance
   * @return yiggFileTools A yiggFileTools instace
   */
  public static function create(sfEventDispatcher $dispatcher = null, sfFormatter $formatter = null)
  {
    return new yiggFileTools($dispatcher, $formatter);
  }

  /**
   * Checks to see if an absolute reference to a file exists or not.
   * @return Boolean
   * @param $file_reference the reference to the file.
   */
  public static function hasFile($file_reference)
  {
    return file_exists($file_reference);
  }

  /**
   * Removes the absolute part of the URL from the given reference.
   * The file being refrenced must be in the "upload" directory in htdocs
   * @return
   * @param $ref Object
   */
  public static function makeUploadRealative($ref)
  {
    $updir = sfConfig::get('sf_upload_dir');
    $directory = substr($ref, 0, strrpos($updir, '/'));
    return str_replace($directory, '', $ref);
  }

  /**
   * Create file with unique file name
   *
   * @param string $prefix The prefix of the generated temporary filename.
   * @return string Returns the new temporary filename, or FALSE on failure.
   */
  public static function tempFileName($prefix)
  {
    return tempnam(sfConfig::get('sf_temp_dir'), $prefix);
  }

  /**
   * Write a string to a file in atomic mode (single operation)
   *
   * The atomic operation is based on rename wich is atomic on Linux but most likely is not on Windows!
   *
   * @param string $filename Path to the file where to write the data.
   * @param string $data The data to write.
   * @param int $mode File mode (octal value)
   * @return int The function returns the number of bytes that were written to the file, or FALSE on failure.
   */
  public static function filePutContentsAtomic($filename, $data, $mode = 0777)
  {
    // Get a temporary file and open it for writing
    $tempFileName = self::tempFileName('atomic_');
    if (false == ($filePointer = @fopen($tempFileName, 'wb')))
    {
      return false;
    }

    // Write content to file and close it
    $bytesWritten = @fwrite($filePointer, $data);
    @fclose($filePointer);

    // If write failed: remove temp file
    if (false == $bytesWritten)
    {
      @unlink($tempFileName);
      return false;
    }

    // If the destination file exists: remove it
    if ((true == @file_exists($filename)) && (false == @unlink($filename)))
    {
      @unlink($tempFileName);
      return false;
    }

    // Rename the temp file to the destination file
    if (false == @rename($tempFileName, $filename))
    {
      @unlink($tempFileName);
      return false;
    }

    // Change file mode
    @chmod($filename, $mode);

    return $bytesWritten;
  }

  /**
   * Creates the directory specified recursively
   *
   * @param String $directory
   * @param integer $fileMode
   * @param boolean $create
   * @param integer $dirMode
   * @return String
   */
  public static function mkdir( $directory, $fileMode = 0666, $create = true, $dirMode = 0777)
  {
    try
    {
      if( is_dir( $directory ) )
	  {
	    @chmod($directory, $dirMode);
	    return $directory;
	  }
	  else
	  {
	    $fmode = 0777;
	    $created = @mkdir( $directory, $dirMode, $create );
	    if ( $create && false == $created )
	    {
	      @chmod($directory, $dirMode);

	      // failed to create the directory
	      $error = 'Failed to create file upload directory "%s"';
	      $error = sprintf( $error, $directory );
	        throw new sfFileException( $error );
	    }

	    // chmod the directory since it doesn't seem to work on
	    @chmod($directory, $dirMode);
	  }
      return $directory;
    }
    catch(Exception $e)
    {
      sfContext::getInstance()->getLogger()->log(
  		 "yiggTools->mkDir(): could not mkdir $directory : " . $e, 3
      );
    }
  }

  /**
   * returns the filesize in bytes of the file (if found)
   * passed to it
   *
   * @param String $path
   * @return int bytes
   */
  public static function absoltuteSize( $path )
  {
    if( file_exists( $path ) )
    {
      $size = filesize( $path );
      return $size;
    }
    else
    {
      return 0;
    }
  }

  /**
   * Returns the size of the file type in an appropriate format.
   * @param Integer
   * @return String
   */
  public static function humanFileSize( $bytes )
  {
    if($bytes)
    {
      if($bytes < 1024) return $bytes . ' bytes';
      if($bytes < 10240) return (round($bytes/1024*10)/10). ' KB';          //1024*10
      if($bytes < 1048576) return round($bytes/1024) . ' KB';            //1024*1024
      if($bytes < 10485760) return (round(($bytes/1024)/1024*10)/10) . ' MB';    //1024*1024*10
      if($bytes < 1073741824) return round(($bytes/1024)/1024) . ' MB';        //1024*1024*1024
    }
  }

  /**
   * Returns boolean if the file exists or not.
   *
   * @param String $path
   * @return Boolean
   */
  public static function fileExists( $path )
  {
    return file_exists( $path );
  }

  /**
   * Removes all illegal charaters from image strings. Also sanitises the
   * image suitable for better google ratings...
   */
  public static function cleanFilename( $string )
  {
    // Replace spaces with dashes. (google friendly)
    $newFilename = str_replace( ' ', '-', $string);

    // Remove any nasty chars
    $newFilename = ereg_replace( '[^A-Za-z0-9+.-]+', '', $newFilename);

    // Remove + || -
    $newFilename = ereg_replace( '-+', '-', $newFilename);

    // Remove the extension
    $newFilename = substr( $newFilename, 0, strrpos( $newFilename,'.') );
    return $newFilename;
  }

  /**
   * Removes the file at the path given.
   *
   * @param String the absolutePath of the file to delete
   * @return Boolean
   */
  public static function delete( $path )
  {
    if(file_exists($path))
    {
      chmod($path, 0755);
      return  unlink($path);
    }
  }

  /**
   * Recursively removes directories and contents from the specified path.
   *
   * @param unknown_type $directory
   * @return unknown
   */
  public static function rmdir( $directory )
  {
    if( !empty($directory) )
    {
      try
      {
        if ( is_dir($directory) )
	    {
          foreach ( scandir( $directory )  as $value )
	      {
            if ( $value != "." && $value != ".." )
            {
              $value = $directory . "/" . $value;
              if ( is_dir( $value ) )
              {
	            self::rmdir( $value );
	          }
	          elseif ( is_file( $value ) )
	          {
	            @unlink( $value );
	          }
            }
          }
          return rmdir( $directory );
        }
        else
        {
          return false;
        }
      }
      catch( Exception $e )
      {
        sfContext::getInstance()->getLogger()->log(
  		 "yiggTools->rmDir(): could not rmdir $directory : " . $e, 3
        );
      }
    }
    else
    {
      throw new Exception("ERROR: Dir not set.");
    }
  }
}