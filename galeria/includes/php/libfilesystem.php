<?php

# to support chinese naming
setlocale(LC_ALL, 'zh_CN.UTF8');
class libfilesystem {

        var $rs = array();

        function libfilesystem(){
        }

        ######################################################################

        function file_escapeshellarg($file){
                return escapeshellarg($file);
        }

        function file_write($body, $file){
                $x = (($fd = fopen($file, "w")) && (fputs($fd, $body))) ? 1 : 0;
                fclose ($fd);
                return $x;
        }

        function file_read($file){
                 clearstatcache();
                 if(file_exists($file) && is_file($file) && filesize($file)!=0){
                    $x =  ($fd = fopen($file, "r")) ? fread($fd,filesize($file)) : "";
                    if ($fd)
                        fclose ($fd);
                 }
                 return $x;

        }

        
        function mb_substr_ed($str, $start, $length=NULL, $encoding=NULL)
        {
        	global $g_encoding_unicode, $intranet_default_lang, $intranet_default_lang_set;

        	if ($length==NULL)
        	{
        		$length = $this->mb_strlen_ed($str);
        	}

        	if (!$g_encoding_unicode && ($intranet_default_lang=="en" || $intranet_default_lang=="b5") && (!isset($intranet_default_lang_set) || (isset($intranet_default_lang_set) && !in_array("gb", $intranet_default_lang_set))) )
        	{
        		# Explicitly specify BIG5 as encoding for ANSI case if system is using either Eng or Big5
				$strNow = (@mb_substr($str, $start, $length, "BIG5"));
        		if ($strNow=="")
        		{
        			$strNow = mb_substr($str, $start, $length);
				}
				return $strNow;
        	} else
        	{
        		return mb_substr($str, $start, $length);
        	}
        }

		function mb_strlen_ed($str, $encoding=NULL)
        {
        	global $g_encoding_unicode, $intranet_default_lang, $intranet_default_lang_set;

        	if (!$g_encoding_unicode && ($intranet_default_lang=="en" || $intranet_default_lang=="b5") && (!isset($intranet_default_lang_set) || (isset($intranet_default_lang_set) && !in_array("gb", $intranet_default_lang_set))) )
        	{
        		# Explicitly specify BIG5 as encoding for ANSI case if system is using either Eng or Big5
        		$length = (@mb_strlen($str, "BIG5"));
        		if ($length=="")
        		{
        			$length = mb_strlen($str);
				}
				return $length;
        	} else
        	{
        		return mb_strlen($str);
        	}
        }



        function file_name($file){
                $file = basename($file);
                return substr($file, 0, strpos($file,"."));
        }
        /*function file_name($file)
        {
	     	$path_info = pathinfo($file);
	     	return $path_info['filename'];
        }*/

        

        function file_remove($file)
        {
                return (file_exists($file)) ? unlink($file) : 0;
        }

        function file_copy($source, $dest){
                $dest .= (is_dir($dest)) ? "/".basename($source) : "";
                return copy($source, $dest);
        }

        function file_rename($oldfile, $newfile){
                return rename($oldfile, $newfile);
        }

        function file_unzip($file, $dest){
                if(is_file($file)) {
                        $file = $this->file_escapeshellarg($file);
                        $dest1 = $this->file_escapeshellarg($dest);
						exec("zip -F $file");
                        exec("unzip $file -d $dest1");
                        //$this->chmod_R($dest, 0755);
						return 1;
                }
                return 0;
        }


        function file_zip($file, $dest, $to_dir="")
        {
                 $file = $this->file_escapeshellarg($file);
                 $dest = $this->file_escapeshellarg($dest);
                 chdir("$to_dir");
                 $Str = exec("zip -r $dest $file");
        }

        function file_set_modification_time($file, $time=""){
                $time = ($time == "") ? time() : $time;
                return touch($file, $time);
        }

         

                # Unzip File Using Shell Commands
                function unzipFile($myFilePath, $myDest, $myPassword=""){
                        $result_arr = array();

                        // copy to temporary file named
                        $tmp_file = session_id()."_".time().".zip";
                        $tmp_src = "/tmp/".$tmp_file;
                        copy($myFilePath, $tmp_src);

                        $dirNow = getcwd();
                        // change to zip file
                        chdir($myDest);
                        exec("zip -F $myPassword \"{$tmp_src}\" ");
                        exec("unzip $myPassword \"{$tmp_src}\" ", $result_arr);

                        // avoid failure of file reading
                        $this->chmod_R($myDest, 0777);

                        // delete temporary file
                        unlink($tmp_src);
                        chdir($dirNow);

                        return $result_arr;
                }

                function chmod_R($path, $filemode)
                {
                        clearstatcache();
                        if (!is_dir($path))
                        {
                                return chmod($path, $filemode);
                        }

                        $dh = opendir($path);
                        
                        while ($file = readdir($dh))
                        {
                                if ($file != '.' && $file != '..')
                                {
                                        $fullpath = $path.'/'.$file;
                                        chmod($fullpath, $filemode);
                                        if(!is_dir($fullpath))
                                        {
                                                if (!chmod($fullpath, $filemode))
                                                        return FALSE;
                                        } else
                                        {
                                                if (!$this->chmod_R($fullpath, $filemode))
                                                        return FALSE;
                                        }
                                }
                        }

                        closedir($dh);

                        if (chmod($path, $filemode))
                                return TRUE;
                        else
                                return FALSE;
                }
        ######################################################################

        /*function folderlist($location){
                # prevent error if folder does not exist
                clearstatcache();
                if (!file_exists($location))
                {
                        return false;
                }

                $d = dir($location);

                while($entry=$d->read()) {
                        $filepath = $location . "/" . $entry;
                        
                        if (is_dir($filepath) && $entry<>"." && $entry<>".."){
                        	
                            	$this->return_folderlist($filepath);
                                //$this->rs[sizeof($this->rs)] = $filepath;
                        }
                        else if (is_file($filepath))
                                $this->rs[sizeof($this->rs)] = $filepath;

                }
                $d->close();
        }*/
        function folderlist($location){
                # prevent error if folder does not exist
                clearstatcache();
                if (!file_exists($location))
                {
                        return false;
                }

                $d = dir($location);

                while($entry=$d->read()) {
                        $filepath = $location . "/" . $entry;
                        
                        if (is_dir($filepath) && $entry<>"." && $entry<>".."){
                        	
                            	$this->return_folderlist($filepath);
                                $this->rs[sizeof($this->rs)] = $filepath;
                        }
                        else if (is_file($filepath))
                                $this->rs[sizeof($this->rs)] = $filepath;

                }
                $d->close();
        }

        function return_folderlist($location){
                        
                        $this->folderlist($location);

                        return $this->rs;
        }

        function return_folder($location)
        {

                        $this->rs = array();              # clear rs

                $folders = array();
                $j = 0;
                $row = $this->return_folderlist($location);


                for($i=0; $i<sizeof($row); $i++){
                        if(is_dir($row[$i])) $folders[$j++] = $row[$i];
                }


                return $folders;
        }

        ######################################################################

        function item_remove($file){
                return (is_file($file)) ? $this->file_remove($file) : $this->folder_remove($file);
        }

        function item_copy($file, $dest){
                return (is_file($file)) ? $this->file_copy($file, $dest) : $this->folder_new($dest);
        }

        ######################################################################

        function folder_new($location){
                #umask(0);
                return (file_exists($location)) ? 0 : mkdir($location, 0777);
        }

        function folder_remove($file){
                return (file_exists($file)) ? rmdir($file) : 0;
        }

        function folder_remove_recursive($location){
                if(is_dir($location)){
                        $row = $this->return_folderlist($location);
                        for($i=0; $i<sizeof($row); $i++){
                                $this->item_remove($row[$i]);
                        }
                        $this->item_remove($location);
                }
                return 1;
        }

        function folder_copy($source, $dest){
                $row = $this->return_folderlist($source);
                $ext = str_replace(substr($source,0,strrpos($source,"/")),'',$source);
                $this->item_copy($source, $dest.$ext);
                for($i=sizeof($row)-1; $i>=0; $i--){
                        $file = $row[$i];
                        if($file=="." || $file=="..") continue;
                        $ext = str_replace(substr($source,0,strrpos($source,"/")),'',$file);
                        $this->item_copy($file, $dest.$ext);
                }
                return 1;
        }

        function folder_size($location){
                $file_size = 0;
                $file_no = 0;
                $dir_no = 0;
                if(is_dir($location)){
                        $row1 = $this->return_folderlist($location);
                        for($i=0; $i<sizeof($row1); $i++){
                                if(is_file($row1[$i])){

                                        $file_size += filesize($row1[$i]); $file_no++;
                                }else{
                                        $dir_no++;
                                }
                        }
                }else{
                        $file_size += filesize($location); $file_no++;
                }
                $size = array($file_size, $file_no, $dir_no);
                return $size;
        }


        function lfs_remove($file){
                return (is_file($file)) ? $this->file_remove($file) : $this->folder_remove_recursive($file);
        }

        function lfs_copy($file, $dest){
                 if ($file=="") return 0;
                if(basename(dirname($file)) == basename($dest)) return 0;
                                if(is_file($file))
                                        return $this->file_copy($file, $dest);
                                else if(is_dir($file))
                                        return $this->folder_copy($file, $dest);
        }

        function lfs_move($file, $dest){
                if(basename(dirname($file)) == basename($dest)) return 0;
                $this->lfs_copy($file, $dest);
                $this->lfs_remove($file);
                return 1;
        }

        function getFileExtension($dest){
		if (is_file($dest))
		{
			return substr(basename($dest), (strpos(basename($dest), ".")));
		} else
		{
			return "";
		}
	}

}
?>