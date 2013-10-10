<?php
class File extends BaseFile
{
  /**
   * Creates a file
   *
   * @param sfValidatedFile $file
   * @param String $path
   * @param String $directory
   * @param String $filename
   * @param String $fileExtension
   * @return File
   */
  public static function create( $file, $path, $directory, $filename, $fileExtension ,$conn = null)
  {
    $file->save($path, 0777, true);
    
    // remove avatars
    yiggFileTools::rmdir($directory."thumbnails/".$filename."/");
    
    $new = new File();
    $directory = $new->getPathRelativeToUploadDir( $directory );
    $new->file_directory = substr( $directory,1, strlen($directory) );
    $new->file_name = $filename;
    $new->file_type = $fileExtension;
    $new->save( $conn );
    return $new;
  }

  public static function createFromValidatedFile( $file, $type, $filename ,$conn = null)
  {
    $fileExtension = $file->getExtension();
    if (empty($fileExtension) || trim($fileExtension) == "")
    {
      $fileExtension = ".".$type;
    }
    $directory = sfConfig::get('sf_upload_dir') . '/images/' . $type . '/';

    $path = $directory . $filename . $fileExtension ;
    return File::create( $file, $path, $directory, $filename, $fileExtension , $conn);
  }

  public function preDelete($event)
  {
    $path = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . $this->getPathOnDisk();

    if( true === file_exists($path))
    {
      $fs = new sfFilesystem();
      $fs->delete($path);
    }

    if($this->id && !empty($this->file_directory) && !empty($this->file_name))
    {
      $directory  = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . $this->file_directory . $this->file_name;
      $fs = new sfFilesystem();
      $fs->delete($directory);
    }
  }

  public function hasFile()
  {
    return file_exists( $this->getPathOnDisk() );
  }

  public function getPathOnDisk()
  {
    return $this->file_directory . $this->file_name . $this->file_type;
  }

  public function getThumbnail($width, $height)
  {
    $thumbnail = $this->getThumbnailFile($width, $height);
    if(false === $thumbnail)
    {
      return false;
    }

    return $thumbnail;
  }

  private function getThumbnailFile($width, $height)
  {
    if(false === $this->hasFile())
    {
      return false;
    }

    $file = new File();
    $file->file_directory = $this->file_directory . "thumbnails/".$this->file_name."/";
    $file->file_name = $width."x".$height;
    $file->file_type  =  ".png";

    if($file->hasFile())
    {
      return $file;
    }

    $thumbnail = new sfThumbnail($width, $height, false, true, 100, 'sfImageMagickAdapter', array('method' => 'shave_all'));
    $thumbnail->loadFile($this->getPathOnDisk());
    $filesystem = new sfFilesystem();
    $filesystem->mkdirs($file->file_directory, 0777);
    $thumbnail->save($file->getPathOnDisk());
    
    chmod($file->getPathOnDisk(), 0777);
    
    return $file;
  }

  private function getPathRelativeToUploadDir($path)
  {
    $upload_dir = sfConfig::get('sf_upload_dir');
    $directory = substr($path, 0, strrpos($upload_dir, '/'));
    return str_replace($directory, '', $path);
  }
}