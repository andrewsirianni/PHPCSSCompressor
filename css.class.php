<?php
	class CSS{
		protected $css		= array();
		protected $filename = '';
		protected $force	= 0;
		protected $referrer	= '';
		protected $doc_root = '';

		public function __construct($phpself,$force=1,$doc_root=''){
			if($doc_root==''){ $doc_root=dirname($_SERVER['DOCUMENT_ROOT']); }
			$this->force=$force;
			$this->referrer=$phpself;
			$this->doc_root=$doc_root;
		}

		public function add($src){
			$this->css[]=$this->doc_root.$src;
			return $this;
		}

		public function compile(){
			$filename='';
			foreach($this->css as $file){
				$filename.=$file;
			}
			$this->filename=md5($filename).'.css.php';
			
			// create folder if it doesn't exist
			if (!file_exists($this->doc_root.'/inc/css/cache/')) {
				mkdir($this->doc_root.'/inc/css/cache/', 0777, true);
			}

			if($this->force==1 && file_exists($this->doc_root.'/inc/css/cache/'.$this->filename)){
				unlink($this->doc_root.'/inc/css/cache/'.$this->filename);
			}

			if(!file_exists($this->doc_root.'/inc/css/cache/'.$this->filename)){
				$csscode='';

				foreach($this->css as $file){
					$csscode.='/*** CSS File: '.$file.' ***/'."\n".file_get_contents($file)."\n\n\n";
				}
				$csscode=$this->compress($csscode);

				$csscode='<?php if(extension_loaded(\'zlib\')){ob_start(\'ob_gzhandler\');} header("Content-Type: text/css"); ?>'."\n".
					$csscode."\n".
					'<?php if(extension_loaded(\'zlib\')){ob_end_flush();}?>';

				$cssfile=fopen($this->doc_root.'/inc/css/cache/'.$this->filename,'w');
				fwrite($cssfile,$csscode);
				fclose($cssfile);
			}

			$res=array();
			$res[0]='OK';
			$res[1]=$this->filename;
			return $res;
		}

		public function compress($buffer) {
			// remove comments
			$buffer=preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$buffer);
			// remove tabs, spaces, newlines, etc.
			$buffer=str_replace(array("\r", "\n", "\t"), '', $buffer);
			$buffer=str_replace('  ', ' ', $buffer);
			$buffer=str_replace(' {', '{', $buffer);
			$buffer=str_replace(';}', '}', $buffer);
			$buffer=preg_replace("!/\ ?([>|\+|,])\ ?/!","\1",$buffer);
			return $buffer;
		}
	}
?>
