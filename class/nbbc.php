<?php
    /*
    This is a compressed copy of NBBC. Do not edit!
    Copyright (c) 2008-9, the Phantom Inker.  All rights reserved.
    Portions Copyright (c) 2004-2008 AddedBytes.com
    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in
      the documentation and/or other materials provided with the
      distribution.
    THIS SOFTWARE IS PROVIDED BY THE PHANTOM INKER "AS IS" AND ANY EXPRESS
    OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
    LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
    CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR
    BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
    WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
    OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
    IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
    */
	class BBCode 
	{
		const BBCODE_PROHIBIT = -1;
		const BBCODE_OPTIONAL = 0;
		const BBCODE_REQUIRED = 1;
		const BBCODE_VERBATIM = 2;
		const BBCODE_CHECK = 1;
		const BBCODE_OUTPUT = 2;
		const BBCODE_EOI = 0;
		const BBCODE_WS = 1;
		const BBCODE_NL = 2;
		const BBCODE_TEXT = 3;
		const BBCODE_TAG = 4;
		const BBCODE_ENDTAG = 5;
		const BBCODE_MODE_SIMPLE = 0;
		const BBCODE_MODE_CALLBACK = 1;
		const BBCODE_MODE_INTERNAL = 2;
		const BBCODE_MODE_LIBRARY = 3;
		const BBCODE_MODE_ENHANCED = 4;
		const BBCODE_STACK_TOKEN = 0;
		const BBCODE_STACK_TEXT = 1;
		const BBCODE_STACK_TAG = 2;
		const BBCODE_STACK_CLASS = 3;
		
		public $smiley_dir;
		public $smiley_url;
		
		protected $tag_rules;
		protected $defaults;
		protected $current_class;
		protected $root_class;
		protected $lost_start_tags;
		protected $start_tags;
		protected $allow_ampersand;
		protected $tag_marker;
		protected $ignore_newlines;
		protected $plain_mode;
		protected $detect_urls;
		protected $url_pattern;
		protected $output_limit;
		protected $text_length;
		protected $was_limited;
		protected $limit_tail;
		protected $limit_precision;
		protected $smileys;
		protected $smiley_regex;
		protected $enable_smileys;
		protected $wiki_url;
		protected $local_img_dir;
		protected $local_img_url;
		protected $url_targetable;
		protected $url_target;
		protected $url_template;
		protected $quote_template;
		protected $wiki_url_template;
		protected $email_template;
		protected $rule_html;
		protected $pre_trim;
		protected $post_trim;
		protected $max_smileys;
		protected $escape_content;
		
		public function __construct(BBCodeLibrary $library = null) 
		{
			$this->defaults = isset($library) ? $library : new BBCodeLibrary();
			$this->tag_rules = $this->defaults->default_tag_rules;
			$this->smileys = $this->defaults->default_smileys;
			$this->enable_smileys = true;
			$this->smiley_regex = false;
			$this->smiley_dir = $this->getDefaultSmileyDir();
			$this->smiley_url = $this->getDefaultSmileyURL();
			$this->wiki_url = $this->getDefaultWikiURL();
			$this->local_img_dir = $this->getDefaultLocalImgDir();
			$this->local_img_url = $this->getDefaultLocalImgURL();
			$this->rule_html = $this->getDefaultRuleHTML();
			$this->pre_trim = "";
			$this->post_trim = "";
			$this->root_class = 'block';
			$this->lost_start_tags = [];
			$this->start_tags = [];
			$this->tag_marker = '[';
			$this->allow_ampsersand = false;
			$this->current_class = $this->root_class;
			$this->ignore_newlines = false;
			$this->output_limit = 0;
			$this->plain_mode = false;
			$this->was_limited = false;
			$this->limit_tail = "...";
			$this->limit_precision = 0.15;
			$this->detect_urls = true;
			$this->url_pattern = '<a href="{$url/h}">{$text/h}</a>';
			$this->url_targetable = false;
			$this->url_target = false;
			$this->url_template = '<a href="{$url/h}" class="bbcode_url"{$target/v}>{$content/v}</a>';
			$this->quote_template = "\n" . '<div class="bbcode_quote">' . "\n" . '<div class="bbcode_quote_head">{$title/v}</div>' . "\n";
			$this->quote_template .= '<div class="bbcode_quote_body">{$content/v}</div>' . "\n</div>\n";
			$this->wiki_url_template = '<a href="{$wikiURL/v}{$name/v}" class="bbcode_wiki">{$title/h}</a>';
			$this->email_template = '<a href="mailto:{$email/h}" class="bbcode_email">{$content/v}</a>';
			$this->max_smileys = -1;
			$this->escape_content = true;
		}

		public function setPreTrim($trim = "a") 
		{
			$this->pre_trim = $trim;
			return $this;
		}

		public function getPreTrim() 
		{
			return $this->pre_trim;
		}

		public function setPostTrim($trim = "a") 
		{
			$this->post_trim = $trim;
			return $this;
		}

		public function getPostTrim() 
		{
			return $this->post_trim;
		}

		public function setRoot($class = 'block') 
		{
			$this->root_class = $class;
			return $this;
		}

		public function setRootInline() 
		{
			$this->root_class = 'inline';
			return $this;
		}

		public function setRootBlock() 
		{
			$this->root_class = 'block';
			return $this;
		}

		public function getRoot() 
		{
			return $this->root_class;
		}

		public function setAllowAmpersand($enable = true) 
		{
			$this->allow_ampersand = $enable;
			return $this;
		}

		public function getAllowAmpersand() 
		{
			return $this->allow_ampersand;
		}

		public function setTagMarker($marker = '[') 
		{
			$this->tag_marker = $marker;
			return $this;
		}

		public function getTagMarker() 
		{
			return $this->tag_marker;
		}

		public function setIgnoreNewlines($ignore = true) 
		{
			$this->ignore_newlines = $ignore;
			return $this;
		}

		public function getIgnoreNewlines() 
		{
			return $this->ignore_newlines;
		}

		public function setLimit($limit = 0) 
		{
			$this->output_limit = $limit;
			return $this;
		}

		public function getLimit() 
		{
			return $this->output_limit;
		}

		public function setLimitTail($tail = "...") 
		{
			$this->limit_tail = $tail;
			return $this;
		}

		public function getLimitTail() 
		{
			return $this->limit_tail;
		}

		public function setLimitPrecision($prec = 0.15) 
		{
			$this->limit_precision = $prec;
			return $this;
		}

		public function getLimitPrecision() 
		{
			return $this->limit_precision;
		}

		public function wasLimited() 
		{
			return $this->was_limited;
		}

		public function setPlainMode($enable = true) 
		{
			$this->plain_mode = $enable;
			return $this;
		}

		public function getPlainMode() 
		{
			return $this->plain_mode;
		}

		public function setDetectURLs($enable = true) 
		{
			$this->detect_urls = $enable;
			return $this;
		}

		public function getDetectURLs() 
		{
			return $this->detect_urls;
		}

		public function setURLPattern($pattern) 
		{
			$this->url_pattern = $pattern;
			return $this;
		}

		public function getURLPattern() 
		{
			return $this->url_pattern;
		}

		public function setURLTargetable($enable) 
		{
			$this->url_targetable = $enable;
			return $this;
		}

		public function getURLTargetable() 
		{
			return $this->url_targetable;
		}

		public function setURLTarget($target) 
		{
			$this->url_target = $target;
			return $this;
		}

		public function getURLTarget() 
		{
			return $this->url_target;
		}

		public function setURLTemplate($template) 
		{
			$this->url_template = $template;
			return $this;
		}

		public function getURLTemplate() 
		{
			return $this->url_template;
		}

		public function setQuoteTemplate($template) 
		{
			$this->quote_template = $template;
			return $this;
		}

		public function getQuoteTemplate() 
		{
			return $this->quote_template;
		}

		public function setWikiURLTemplate($template) 
		{
			$this->wiki_url_template = $template;
			return $this;
		}

		public function getWikiURLTemplate() 
		{
			return $this->wiki_url_template;
		}

		public function setEmailTemplate($template) 
		{
			$this->email_template = $template;
			return $this;
		}

		public function getEmailTemplate() 
		{
			return $this->email_template;
		}

		public function setEscapeContent($escape_content) 
		{
			$this->escape_content = $escape_content;
			return $this;
		}

		public function getEscapeContent() 
		{
			return $this->escape_content;
		}

		public function addRule($name, $rule) 
		{
			$this->tag_rules[$name] = $rule;
			return $this;
		}

		public function removeRule($name) 
		{
			unset($this->tag_rules[$name]);
			return $this;
		}

		public function getRule($name) 
		{
			return isset($this->tag_rules[$name]) ? $this->tag_rules[$name] : false;
		}

		public function clearRules() 
		{
			$this->tag_rules = [];
			return $this;
		}

		public function getDefaultRule($name) 
		{
			return isset($this->defaults->default_tag_rules[$name]) ? $this->defaults->default_tag_rules[$name] : false;
		}

		public function setDefaultRule($name) 
		{
			if(isset($this->defaults->default_tag_rules[$name])):
				$this->addRule($name, $this->defaults->default_tag_rules[$name]);
			else:
				$this->removeRule($name);
			endif;
		}

		public function getDefaultRules() 
		{
			return $this->defaults->default_tag_rules;
		}

		public function setDefaultRules() 
		{
			$this->tag_rules = $this->defaults->default_tag_rules;
			return $this;
		}

		public function setWikiURL($url) 
		{
			$this->wiki_url = $url;
			return $this;
		}

		public function getWikiURL() 
		{
			return $this->wiki_url;
		}

		public function getDefaultWikiURL() 
		{
			return '/?page=';
		}

		public function setLocalImgDir($path) 
		{
			$this->local_img_dir = $path;
			return $this;
		}

		public function getLocalImgDir() 
		{
			return $this->local_img_dir;
		}

		public function getDefaultLocalImgDir() 
		{
			return "img";
		}

		public function setLocalImgURL($path) 
		{
			$this->local_img_url = rtrim($path, '/');
			return $this;
		}

		public function getLocalImgURL()
		{
			return $this->local_img_url;
		}

		public function getDefaultLocalImgURL() 
		{
			return "img";
		}

		public function setRuleHTML($html) 
		{
			$this->rule_html = $html;
			return $this;
		}

		public function getRuleHTML() 
		{
			return $this->rule_html;
		}

		public function getDefaultRuleHTML() 
		{
			return "\n<hr class=\"bbcode_rule\" />\n";
		}

		public function addSmiley($code, $image) 
		{
			$this->smileys[$code] = $image;
			$this->smiley_regex = false;
			return $this;
		}

		public function removeSmiley($code) 
		{
			unset($this->smileys[$code]);
			$this->smiley_regex = false;
			return $this;
		}

		public function getSmiley($code) 
		{
			return isset($this->smileys[$code]) ? $this->smileys[$code] : false;
		}

		public function clearSmileys() 
		{
			$this->smileys = [];
			$this->smiley_regex = false;
			return $this;
		}

		public function getDefaultSmiley($code) 
		{
			return isset($this->defaults->default_smileys[$code]) ? $this->defaults->default_smileys[$code] : false;
		}

		public function setDefaultSmiley($code) 
		{
			if(isset($this->defaults->default_smileys[$code])):
				$this->smileys[$code] = $this->defaults->default_smileys[$code];
			endif;
			
			$this->smiley_regex = false;
			return $this;
		}

		public function getDefaultSmileys() 
		{
			return $this->defaults->default_smileys;
		}

		public function setDefaultSmileys() 
		{
			$this->smileys = $this->defaults->default_smileys;
			$this->smiley_regex = false;
			return $this;
		}

		public function setSmileyDir($path) 
		{
			$this->smiley_dir = $path;
			return $this;
		}

		public function getSmileyDir() 
		{
			return $this->smiley_dir;
		}

		public function getDefaultSmileyDir() 
		{
			return "smileys";
		}

		public function setSmileyURL($path) 
		{
			$this->smiley_url = $path;
			return $this;
		}

		public function getSmileyURL() 
		{
			return $this->smiley_url;
		}

		public function getDefaultSmileyURL() 
		{
			return "smileys";
		}

		public function setEnableSmileys($enable = true) 
		{
			$this->enable_smileys = $enable;
			return $this;
		}

		public function getEnableSmileys() 
		{
			return $this->enable_smileys;
		}

		public function setMaxSmileys($count) 
		{
			$this->max_smileys = (int)$count;
			if($this->max_smileys < -1):
				$this->max_smileys = -1;
			endif;
			
			return $this;
		}

		public function getMaxSmileys() 
		{
			return $this->max_smileys;
		}

		public function nl2br($string) 
		{
			return preg_replace("/\\x0A|\\x0D|\\x0A\\x0D|\\x0D\\x0A/", "<br>\n", $string);
		}

		public function unHTMLEncode($string) 
		{
			return html_entity_decode($string);
		}

		public function wikify($string) 
		{
			return rawurlencode(str_replace(" ", "_", trim(preg_replace("/[!?;@#\$%\\^&*<>=+`~\\x00-\\x20_-]+/", " ", $string))));
		}

		public function isValidURL($string, $email_too = true) 
		{
			if(filter_var($string, FILTER_VALIDATE_URL) !== false &&
				in_array(parse_url($string, PHP_URL_SCHEME), ['http', 'https', 'ftp'])):
				return true;
			endif;

			if(preg_match("/^[^:]+([\\/\\\\?#][^\\r\\n]*)?$/D", $string)):
				return true;
			endif;

			if($email_too && substr($string, 0, 7) == "mailto:"):
				return $this->isValidEmail(substr($string, 7));
			endif;

			return false;
		}

		protected function isValidURLPHP($string, $email_too = true) 
		{
			if(preg_match("/^
					(?:https?|ftp):\\/\\/
					(?:
						(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\\.)+
						[a-zA-Z0-9]
						(?:[a-zA-Z0-9-]*[a-zA-Z0-9])?
					|
						\\[
						(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}
						(?:
							25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-zA-Z0-9-]*[a-zA-Z0-9]:
							(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21-\\x5A\\x53-\\x7F]
								|\\\\[\\x01-\\x09\\x0B\\x0C\\x0E-\\x7F])+
						)
						\\]
					)
					(?::[0-9]{1,5})?
					(?:[\\/\\?\\#][^\\n\\r]*)?
					$/Dx", $string)):
				return true;
			endif;

			if(preg_match("/^[^:]+([\\/\\\\?#][^\\r\\n]*)?$/D", $string)):
				return true;
			endif;

			if($email_too):
				if(substr($string, 0, 7) == "mailto:"):
					return $this->isValidEmail(substr($string, 7));
				endif;
			endif;

			return false;
		}

		public function isValidEmail($string) 
		{
			$result = filter_var($string, FILTER_VALIDATE_EMAIL);
			return $result !== false;
		}

		public function htmlEncode($string) 
		{
			if($this->escape_content):
				if(!$this->allow_ampersand):
					return htmlspecialchars($string);
				else:
					return str_replace(['<', '>', '"'], ['&lt;', '&gt;', '&quot;'], $string);
				endif;
			else:
				return $string;
			endif;
		}
		
		public function fixupOutput($string) 
		{
			if(!$this->detect_urls):
				$output = $this->processSmileys($string);
			else:
				$chunks = $this->autoDetectURLs($string);
				$output = [];
				if(count($chunks)):
					$is_a_url = false;
					foreach($chunks as $index => $chunk):
						if(!$is_a_url):
							$chunk = $this->processSmileys($chunk);
						endif;
						$output[] = $chunk;
						$is_a_url = !$is_a_url;
					endforeach;
				endif;
				$output = implode("", $output);
			endif;

			return $output;
		}

		protected function processSmileys($string) 
		{
			if(!$this->enable_smileys || $this->plain_mode):
				$output = $this->htmlEncode($string);
			else:
				if($this->smiley_regex === false):
					$this->rebuildSmileys();
				endif;

				$tokens = preg_split($this->smiley_regex, $string, -1, PREG_SPLIT_DELIM_CAPTURE);

				if(count($tokens) <= 1):
					$output = $this->htmlEncode($string);
				else:
					$output = "";
					$is_a_smiley = false;
					$smiley_count = 0;
					foreach($tokens as $token):
						if(!$is_a_smiley):
							$output .= $this->htmlEncode($token);
						else:
							$alt = htmlspecialchars($token);
							if($smiley_count < $this->max_smileys || $this->max_smileys < 0):
								$output .= "<img src=\"".htmlspecialchars($this->smiley_url.'/'.$this->smileys[$token]).'"'
									." alt=\"$alt\" title=\"$alt\" class=\"bbcode_smiley\" />";
							else:
								$output .= $token;
							endif;
							$smiley_count++;
						endif;
						$is_a_smiley = !$is_a_smiley;
					endforeach;
				endif;
			endif;

			return $output;
		}

		protected function rebuildSmileys() 
		{
			$regex = ["/(?<![\\w])("];
			$first = true;
			
			foreach($this->smileys as $code => $filename):
				if(!$first)
					$regex[] = "|";
				$regex[] = preg_quote("$code", '/');
				$first = false;
			endforeach;
			
			$regex[] = ")(?![\\w])/";
			$this->smiley_regex = implode("", $regex);
		}
		
		protected function autoDetectURLs($string) 
		{
			$hostRegex = 
				<<<REGEX
				(?:
					(?:[a-zA-Z0-9_-]+(?:\.[a-zA-Z0-9_-]+)*\.[a-z]{2,}(?::\d+)?)
					|
					(?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(?::\d+)?)
				)
				REGEX;

			$urlRegex = 
				<<<REGEX
				(?: 
					(?:(?:https?|ftp)://)? 
					$hostRegex 
					(?:
						(?=[/?#])
						[@a-zA-Z0-9!#-'*-.:;\/;?-z~=]*[a-zA-Z0-9#/=]
					)?
				)
				REGEX;

			$emailRegex = 
				<<<REGEX
				(?: 
					[a-zA-Z0-9._-]+
					@
					[a-zA-Z0-9_-]+(?:\.[a-zA-Z0-9_-]+)*\.[a-z]{2,} 
				)
				REGEX;

			$regex = 
				<<<REGEX
				`
				(?<=^|[\s(]) 
				(
					$urlRegex
					|
					$emailRegex
				)
				(?=$|[\s)]|[.!?;]($|[\s])) 
				`Dx
				REGEX;
				
			$parts = preg_split($regex, $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$result = [];
			$isURL = false;
			
			foreach($parts as $part):
				if(strpos($part, ' ') !== false):
					$urlParts = false;
				else:
					$urlParts = parse_url($part);
				endif;

				if($urlParts !== false && preg_match("`^$hostRegex$`Dx", $part)):
					$urlParts['host'] = $part;
					unset($urlParts['path']);
				endif;

				if(preg_match("`^$emailRegex$`Dx", $part)):
					$urlParts = [
						'url' => "mailto:$part",
						'host' => $part
					];
				endif;

				if($urlParts !== false && empty($urlParts['scheme']) && !empty($urlParts['host'])
					&& !$this->isValidTLD($urlParts['host'], true)):
					$urlParts = false;
				endif;

				if($urlParts === false || empty($urlParts['host'])):
					if($isURL):
						$result[] = '';
					endif;
					$result[] = $part;
				else:
					if(!$isURL):
						$result[] = '';
					endif;

					if(empty($urlParts['url'])):
						$url = $part;
						if(empty($urlParts['scheme'])):
							$url = 'http://'.$part;
						endif;
						$urlParts['url'] = $url;
					endif;

					$urlParts['link'] = $urlParts['url'];
					$urlParts['text'] = $part;
					$result[] = $this->fillTemplate($this->url_pattern, $urlParts);
				endif;
				$isURL = !$isURL;
			endforeach;

			return $result;
		}

		public function isValidTLD($host, $allowIPs = false) 
		{
			static $validTLDs = [
				'aero', 'arpa', 'biz', 'com', 'coop', 'dev', 'edu', 'example', 'gov', 'org', 'info', 'int', 'invalid',
				'local', 'mil', 'museum', 'name', 'net', 'onion', 'pro', 'swift', 'test'
			];

			if($allowIPs && filter_var($host, FILTER_VALIDATE_IP) !== false):
				return true;
			endif;

			if(strpos($host, '.') === false):
				return $host === 'localhost';
			endif;
			
			$tld = trim(strrchr($host, '.'), '.');

			if(in_array($tld, $validTLDs) || preg_match('`^[a-z]{2}$`', $tld)):
				return true;
			endif;

			return false;
		}

		public function fillTemplate($template, $insert_array, $default_array = []) 
		{
			$pieces = preg_split('/(\{\$[a-zA-Z0-9_.:\/-]+\})/', $template, -1, PREG_SPLIT_DELIM_CAPTURE);

			if(count($pieces) <= 1)
				return $template;

			$result = [];

			$is_an_insert = false;
			foreach($pieces as $piece):
				if(!$is_an_insert):
					$result[] = $piece;
				elseif(!preg_match('/\{\$([a-zA-Z0-9_:-]+)((?:\\.[a-zA-Z0-9_:-]+)*)(?:\/([a-zA-Z0-9_:-]+))?\}/', $piece, $matches)):
					$result[] = $piece;
				else:
					if(isset($insert_array[$matches[1]])):
						$value = $insert_array[$matches[1]];
					else:
						$value = isset($default_array[$matches[1]]) ? $default_array[$matches[1]] : null;
					endif;

					if(!empty($matches[2])):
						foreach(explode(".", substr($matches[2], 1)) as $index):
							if(is_array($value)):
								$value = isset($value[$index]) ? $value[$index] : null;
							elseif(is_object($value)):
								$value = isset($value->$index) ? $value->$index : null;
							else:
								$value = '';
							endif;
						endforeach;
					endif;

					switch(gettype($value)):
						case 'boolean':
							$value = $value ? "true" : "false";
							break;
						case 'integer':
							$value = (string)$value;
							break;
						case 'double':
							$value = (string)$value;
							break;
						case 'string':
							break;
						default:
							$value = "";
							break;
					endswitch;

					if(!empty($matches[3])):
						$flags = array_flip(str_split($matches[3]));
					else:
						$flags = [];
					endif;

					if(!isset($flags['v'])):
						if(isset($flags['w'])):
							$value = preg_replace("/[\\x00-\\x09\\x0B-\x0C\x0E-\\x20]+/", " ", $value);
						endif;
						if(isset($flags['t'])):
							$value = trim($value);
						endif;
						if(isset($flags['b'])):
							$value = basename($value);
						endif;
						if(isset($flags['e'])):
							$value = $this->htmlEncode($value);
						elseif(isset($flags['k'])):
							$value = $this->wikify($value);
						elseif(isset($flags['h'])):
							$value = htmlspecialchars($value);
						elseif(isset($flags['u'])):
							$value = urlencode($value);
						endif;
						if(isset($flags['n'])):
							$value = $this->nl2br($value);
						endif;
					endif;

					$result[] = $value;
				endif;

				$is_an_insert = !$is_an_insert;
			endforeach;

			return implode('', $result);
		}

		protected function collectText($array, $start = 0) 
		{
			ob_start();
			
			for($start = intval($start), $end = count($array); $start < $end; $start++):
				print $array[$start][self::BBCODE_STACK_TEXT];
			endfor;
			
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

		protected function collectTextReverse($array, $start = 0, $end = 0) 
		{
			ob_start();
			
			for($start = intval($start); $start >= $end; $start--):
				print $array[$start][self::BBCODE_STACK_TEXT];
			endfor;
			
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}
		
		protected function generateOutput($pos) 
		{
			$output = [];
			
			while(count($this->stack) > $pos):
				$token = array_pop($this->stack);
				if($token[self::BBCODE_STACK_TOKEN] != self::BBCODE_TAG):
					$output[] = $token;
				else:
					$name = isset($token[self::BBCODE_STACK_TAG]['_name']) ? $token[self::BBCODE_STACK_TAG]['_name'] : null;
					$rule = isset($this->tag_rules[$name]) ? $this->tag_rules[$name] : null;
					$rule += [
						'end_tag' => self::BBCODE_REQUIRED, 'before_endtag' => null, 'after_tag' => null, 'before_tag' => null
					];
					$end_tag = $rule['end_tag'];
					array_pop($this->start_tags[$name]);
					
					if($end_tag == self::BBCODE_PROHIBIT):
						$output[] = Array(
							self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
							self::BBCODE_STACK_TAG => false,
							self::BBCODE_STACK_TEXT => $token[self::BBCODE_STACK_TEXT],
							self::BBCODE_STACK_CLASS => $this->current_class,
						);
					else:
						if($end_tag == self::BBCODE_REQUIRED):
							if(!isset($this->lost_start_tags[$name])):
								$this->lost_start_tags[$name] = 0;
							endif;
							$this->lost_start_tags[$name]++;
						endif;

						$end = $this->cleanupWSByIteratingPointer($rule['before_endtag'], 0, $output);
						$this->cleanupWSByPoppingStack($rule['after_tag'], $output);
						$tag_body = $this->collectTextReverse($output, count($output) - 1, $end);
						$this->cleanupWSByPoppingStack($rule['before_tag'], $this->stack);

						if(isset($token[self::BBCODE_STACK_TAG])):
							$this->updateParamsForMissingEndTag($token[self::BBCODE_STACK_TAG]);

							$tag_output = $this->doTag(
								self::BBCODE_OUTPUT,
								$name,
								isset($token[self::BBCODE_STACK_TAG]['_default']) ? $token[self::BBCODE_STACK_TAG]['_default'] : null,
								$token[self::BBCODE_STACK_TAG],
								$tag_body
							);
						else:
							$tag_output = $this->doTag(
								self::BBCODE_OUTPUT,
								$name,
								null,
								null,
								$tag_body
							);
						endif;

						$output = Array(Array(
							self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
							self::BBCODE_STACK_TAG => false,
							self::BBCODE_STACK_TEXT => $tag_output,
							self::BBCODE_STACK_CLASS => $this->current_class
						));
					endif;
				endif;
			endwhile;
			
			$this->computeCurrentClass();
			return $output;
		}

		protected function rewindToClass($class_list) 
		{
			$pos = count($this->stack) - 1;
			while($pos >= 0 && !in_array($this->stack[$pos][self::BBCODE_STACK_CLASS], $class_list))
				$pos--;
			
			if($pos < 0):
				if (!in_array($this->root_class, $class_list))
					return false;
			endif;

			$output = $this->generateOutput($pos + 1);

			while(count($output)):
				$token = array_pop($output);
				$token[self::BBCODE_STACK_CLASS] = $this->current_class;
				$this->stack[] = $token;
			endwhile;

			return true;
		}

		protected function finishTag($tag_name) 
		{
			if(strlen($tag_name) <= 0)
				return false;

			if(isset($this->start_tags[$tag_name]) && count($this->start_tags[$tag_name]))
				$pos = array_pop($this->start_tags[$tag_name]);
			else
				$pos = -1;

			if($pos < 0)
				return false;

			if(isset($this->tag_rules[$tag_name]) && isset($this->tag_rules[$tag_name]['after_tag'])):
				$newpos = $this->cleanupWSByIteratingPointer(
					isset($this->tag_rules[$tag_name]['after_tag']) ? $this->tag_rules[$tag_name]['after_tag'] : null,
					$pos + 1,
					$this->stack
				);
			else:
				$newpos = $this->cleanupWSByIteratingPointer(null, $pos + 1, $this->stack);
			endif;
			
			$delta = $newpos - ($pos + 1);
			$output = $this->generateOutput($newpos);

			if(isset($this->tag_rules[$tag_name]) && isset($this->tag_rules[$tag_name]['before_endtag'])):
				$newend = $this->cleanupWSByIteratingPointer($this->tag_rules[$tag_name]['before_endtag'], 0, $output);
			else:
				$newend = $this->cleanupWSByIteratingPointer(null, 0, $output);
			endif;
			
			$output = $this->collectTextReverse($output, count($output) - 1, $newend);

			while($delta-- > 0)
				array_pop($this->stack);
			$this->computeCurrentClass();

			return $output;
		}

		protected function computeCurrentClass() 
		{
			if(count($this->stack) > 0)
				$this->current_class = $this->stack[count($this->stack) - 1][self::BBCODE_STACK_CLASS];
			else
				$this->current_class = $this->root_class;
		}

		protected function dumpStack($array = false, $raw = false) 
		{
			if(!$raw)
				$string = "<span style='color: #00C;'>";
			else
				$string = "";
			if($array === false)
				$array = $this->stack;
			
			foreach($array as $item):
				$item += [self::BBCODE_STACK_TOKEN => null, self::BBCODE_STACK_TEXT => null, self::BBCODE_STACK_TAG => ['_name' => '']];
				switch($item[self::BBCODE_STACK_TOKEN]):
					case self::BBCODE_TEXT:
						$string .= "\"".htmlspecialchars($item[self::BBCODE_STACK_TEXT])."\" ";
						break;
					case self::BBCODE_WS:
						$string .= "WS ";
						break;
					case self::BBCODE_NL:
						$string .= "NL ";
						break;
					case self::BBCODE_TAG:
						$string .= "[".htmlspecialchars($item[self::BBCODE_STACK_TAG]['_name'])."] ";
						break;
					default:
						$string .= "unknown ";
						break;
				endswitch;
			endforeach;
			
			if (!$raw)
				$string .= "</span>";
			
			return $string;
		}

		protected function cleanupWSByPoppingStack($pattern, &$array) 
		{
			if(strlen($pattern) <= 0)
				return;

			$oldlen = count($array);
			foreach(str_split($pattern) as $char):
				switch($char):
					case 's':
						while (count($array) > 0 && $array[count($array) - 1][self::BBCODE_STACK_TOKEN] == self::BBCODE_WS)
							array_pop($array);
						break;
					case 'n':
						if (count($array) > 0 && $array[count($array) - 1][self::BBCODE_STACK_TOKEN] == self::BBCODE_NL)
							array_pop($array);
						break;
					case 'a':
						while (count($array) > 0 && (($token = $array[count($array) - 1][self::BBCODE_STACK_TOKEN]) == self::BBCODE_WS || $token == self::BBCODE_NL))
							array_pop($array);
						break;
				endswitch;
			endforeach;

			if(count($array) != $oldlen):
				$this->computeCurrentClass();
			endif;
		}

		protected function cleanupWSByEatingInput($pattern) 
		{
			if(strlen($pattern) <= 0)
				return;

			foreach(str_split($pattern) as $char):
				switch($char):
					case 's':
						$token_type = $this->lexer->NextToken();
						while ($token_type == self::BBCODE_WS) {
							$token_type = $this->lexer->NextToken();
						}
						$this->lexer->UngetToken();
						break;
					case 'n':
						$token_type = $this->lexer->NextToken();
						if ($token_type != self::BBCODE_NL)
							$this->lexer->UngetToken();
						break;
					case 'a':
						$token_type = $this->lexer->NextToken();
						while ($token_type == self::BBCODE_WS || $token_type == self::BBCODE_NL) {
							$token_type = $this->lexer->NextToken();
						}
						$this->lexer->UngetToken();
						break;
				endswitch;
			endforeach;
		}

		protected function cleanupWSByIteratingPointer($pattern, $pos, $array) 
		{
			if(strlen($pattern) <= 0)
				return $pos;

			foreach(str_split($pattern) as $char):
				switch($char):
					case 's':
						while ($pos < count($array) && $array[$pos][self::BBCODE_STACK_TOKEN] == self::BBCODE_WS)
							$pos++;
						break;
					case 'n':
						if ($pos < count($array) && $array[$pos][self::BBCODE_STACK_TOKEN] == self::BBCODE_NL)
							$pos++;
						break;
					case 'a':
						while ($pos < count($array) && (($token = $array[$pos][self::BBCODE_STACK_TOKEN]) == self::BBCODE_WS || $token == self::BBCODE_NL))
							$pos++;
						break;
				endswitch;
			endforeach;
			
			return $pos;
		}

		protected function limitText($string, $limit) 
		{
			$chunks = preg_split("/([\\x00-\\x20]+)/", $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$output = "";
			
			foreach($chunks as $chunk):
				if(strlen($output) + strlen($chunk) > $limit)
					break;
				$output .= $chunk;
			endforeach;
			
			$output = rtrim($output);

			return $output;
		}
		
		protected function doLimit() 
		{
			$this->cleanupWSByPoppingStack("a", $this->stack);

			if(strlen($this->limit_tail) > 0):
				$this->stack[] = Array(
					self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
					self::BBCODE_STACK_TEXT => $this->limit_tail,
					self::BBCODE_STACK_TAG => false,
					self::BBCODE_STACK_CLASS => $this->current_class,
				);
			endif;

			$this->was_limited = true;
		}

		public function doTag($action, $tag_name, $default_value, $params, $contents) 
		{
			$tag_rule = isset($this->tag_rules[$tag_name]) ? $this->tag_rules[$tag_name] : null;

			switch($action):
				case self::BBCODE_CHECK:
					if(isset($tag_rule['allow'])):
						foreach($tag_rule['allow'] as $param => $pattern):
							if($param == '_content'):
								$value = $contents;
							elseif($param == '_defaultcontent'):
								if(strlen($default_value)):
									$value = $default_value;
								else:
									$value = $contents;
								endif;
							else:
								if(isset($params[$param])):
									$value = $params[$param];
								else:
									$value = isset($tag_rule['default'][$param]) ? $tag_rule['default'][$param] : null;
								endif;
							endif;
							if(!preg_match($pattern, $value)):
								return false;
							endif;
						endforeach;
						return true;
					endif;

					$result = true;
					if(isset($tag_rule['mode'])):
						$tag_rule += ['method' => ''];
						switch($tag_rule['mode']):
							default:
							case self::BBCODE_MODE_SIMPLE:
								$result = true;
								break;
							case self::BBCODE_MODE_ENHANCED:
								$result = true;
								break;
							case self::BBCODE_MODE_INTERNAL:
								if(method_exists($this, $tag_rule['method'])):
									$result = @call_user_func([$this, $tag_rule['method']], self::BBCODE_CHECK, $tag_name, $default_value, $params, $contents);
								endif;
								break;
							case self::BBCODE_MODE_LIBRARY:
								if(method_exists($this->defaults, $tag_rule['method'])):
									$result = @call_user_func([$this->defaults, $tag_rule['method']], $this, self::BBCODE_CHECK, $tag_name, $default_value, $params, $contents);
								endif;
								break;
							case self::BBCODE_MODE_CALLBACK:
								if(is_callable($tag_rule['method'])):
									$result = @call_user_func($tag_rule['method'], $this, self::BBCODE_CHECK, $tag_name, $default_value, $params, $contents);
								endif;
								break;
						endswitch;
					endif;
					return $result;
				case self::BBCODE_OUTPUT:
					if($this->plain_mode):
						if(!isset($tag_rule['plain_content']))
							$plain_content = Array('_content');
						else
							$plain_content = $tag_rule['plain_content'];

						$result = $possible_content = "";
						foreach($plain_content as $possible_content):
							if($possible_content == '_content' && strlen($contents) > 0):
								$result = $contents;
								break;
							endif;
							if(isset($params[$possible_content]) && strlen($params[$possible_content]) > 0):
								$result = htmlspecialchars($params[$possible_content]);
								break;
							endif;
						endforeach;

						$start = @$tag_rule['plain_start'];
						$end = @$tag_rule['plain_end'];

						if(isset($tag_rule['plain_link'])):
							$link = $possible_content = "";
							
							foreach($tag_rule['plain_link'] as $possible_content):
								if($possible_content == '_content' && strlen($contents) > 0):
									$link = $this->unHTMLEncode(strip_tags($contents));
									break;
								endif;
								if(isset($params[$possible_content]) && strlen($params[$possible_content]) > 0):
									$link = $params[$possible_content];
									break;
								endif;
							endforeach;
							
							$params = parse_url($link);
							
							if(!is_array($params)):
								$params = [];
							endif;
							
							$params['link'] = $link;
							$params['url'] = $link;
							$start = $this->fillTemplate($start, $params);
							$end = $this->fillTemplate($end, $params);
						endif;

						return $start.$result.$end;
					endif;

					$tag_rule += ['mode' => self::BBCODE_MODE_SIMPLE];
					
					switch($tag_rule['mode']):
						default:
						case self::BBCODE_MODE_SIMPLE:
							$result = @$tag_rule['simple_start'].$contents.@$tag_rule['simple_end'];
							break;
						case self::BBCODE_MODE_ENHANCED:
							$result = $this->doEnhancedTag($tag_rule, $params, $contents);
							break;
						case self::BBCODE_MODE_INTERNAL:
							$result = @call_user_func(Array($this, @$tag_rule['method']), self::BBCODE_OUTPUT, $tag_name, $default_value, $params, $contents);
							break;
						case self::BBCODE_MODE_LIBRARY:
							$result = @call_user_func(Array($this->defaults, @$tag_rule['method']), $this, self::BBCODE_OUTPUT, $tag_name, $default_value, $params, $contents);
							break;
						case self::BBCODE_MODE_CALLBACK:
							$result = @call_user_func(@$tag_rule['method'], $this, self::BBCODE_OUTPUT, $tag_name, $default_value, $params, $contents);
							break;
					endswitch;
					return $result;
				default:
					return false;
			endswitch;
		}

		protected function doEnhancedTag($tag_rule, $params, $contents) 
		{
			$params['_content'] = $contents;
			$params['_defaultcontent'] = !empty($params['_default']) ? $params['_default'] : $contents;

			if(isset($tag_rule['template'])):
				if(isset($tag_rule['default'])):
					return $this->fillTemplate($tag_rule['template'], $params, $tag_rule['default']);
				else:
					return $this->fillTemplate($tag_rule['template'], $params, null);
				endif;
			else:
				if(isset($tag_rule['default'])):
					return $this->fillTemplate(null, $params, $tag_rule['default']);
				else:
					return $this->fillTemplate(null, $params, null);
				endif;
			endif;
		}

		protected function updateParamsForMissingEndTag(&$params) 
		{
			switch($this->tag_marker):
				case '[':
					$tail_marker = ']';
					break;
				case '<':
					$tail_marker = '>';
					break;
				case '{':
					$tail_marker = '}';
					break;
				case '(':
					$tail_marker = ')';
					break;
				default:
					$tail_marker = $this->tag_marker;
					break;
			endswitch;
			
			$params['_endtag'] = $this->tag_marker.'/'.$params['_name'].$tail_marker;
		}

		protected function processIsolatedTag($tag_name, $tag_params, $tag_rule) 
		{
			$tag_rule += ['_default' => null, 'before_tag' => null, 'after_tag' => null];

			if(!$this->doTag(self::BBCODE_CHECK, $tag_name, $tag_params['_default'], $tag_params, "")):
				$this->stack[] = Array(
					self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
					self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
					self::BBCODE_STACK_TAG => false,
					self::BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			endif;

			$this->cleanupWSByPoppingStack($tag_rule['before_tag'], $this->stack);
			$output = $this->doTag(self::BBCODE_OUTPUT, $tag_name, $tag_params['_default'], $tag_params, "");
			$this->cleanupWSByEatingInput($tag_rule['after_tag']);

			$this->stack[] = Array(
				self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
				self::BBCODE_STACK_TEXT => $output,
				self::BBCODE_STACK_TAG => false,
				self::BBCODE_STACK_CLASS => $this->current_class,
			);
		}

		protected function processVerbatimTag($tag_name, $tag_params, $tag_rule) 
		{
			$state = $this->lexer->SaveState();
			$end_tag = $this->lexer->tagmarker."/".$tag_name.$this->lexer->end_tagmarker;
			$start = count($this->stack);
			$this->lexer->verbatim = true;
			
			while(($token_type = $this->lexer->NextToken()) != self::BBCODE_EOI):
			
				if(strcasecmp($this->lexer->text, $end_tag) === 0):
					$end_tag_params = $end_tag;
					break;
				endif;

				if($this->output_limit > 0 && $this->text_length + strlen($this->lexer->text) >= $this->output_limit):
					$text = $this->limitText($this->lexer->text, $this->output_limit - $this->text_length);
					if(strlen($text) > 0):
						$this->text_length += strlen($text);
						$this->stack[] = Array(
							self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
							self::BBCODE_STACK_TEXT => $this->fixupOutput($text),
							self::BBCODE_STACK_TAG => false,
							self::BBCODE_STACK_CLASS => $this->current_class,
						);
					endif;
					$this->doLimit();
					break;
				endif;
				
				$this->text_length += strlen($this->lexer->text);
				$this->stack[] = Array(
					self::BBCODE_STACK_TOKEN => $token_type,
					self::BBCODE_STACK_TEXT => htmlspecialchars($this->lexer->text),
					self::BBCODE_STACK_TAG => $this->lexer->tag,
					self::BBCODE_STACK_CLASS => $this->current_class,
				);
			endwhile;
			
			$this->lexer->verbatim = false;

			if($token_type == self::BBCODE_EOI):
				$this->lexer->RestoreState($state);
				$this->stack = array_slice($this->stack, 0, $start);

				$this->stack[] = Array(
					self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
					self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
					self::BBCODE_STACK_TAG => false,
					self::BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			endif;

			if(isset($tag_rule['after_tag'])):
				$newstart = $this->cleanupWSByIteratingPointer($tag_rule['after_tag'], $start, $this->stack);
			else:
				$newstart = $this->cleanupWSByIteratingPointer(null, $start, $this->stack);
			endif;
			
			if(isset($tag_rule['before_endtag'])):
				$this->cleanupWSByPoppingStack($tag_rule['before_endtag'], $this->stack);
			else:
				$this->cleanupWSByPoppingStack(null, $this->stack);
			endif;
			
			if(isset($tag_rule['after_endtag'])):
				$this->cleanupWSByEatingInput($tag_rule['after_endtag']);
			else:
				$this->cleanupWSByEatingInput(null);
			endif;

			$content = $this->collectText($this->stack, $newstart);
			
			array_splice($this->stack, $start);
			$this->computeCurrentClass();

			if(isset($tag_rule['before_tag'])):
				$this->cleanupWSByPoppingStack($tag_rule['before_tag'], $this->stack);
			else:
				$this->cleanupWSByPoppingStack(null, $this->stack);
			endif;

			$tag_params['_endtag'] = $end_tag_params;
			$tag_params['_hasend'] = true;
			
			if(!isset($tag_params['_default'])):
				$tag_params['_default'] = null;
			endif;
			
			$output = $this->doTag(self::BBCODE_OUTPUT, $tag_name, $tag_params['_default'], $tag_params, $content);

			$this->stack[] = Array(
				self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
				self::BBCODE_STACK_TEXT => $output,
				self::BBCODE_STACK_TAG => false,
				self::BBCODE_STACK_CLASS => $this->current_class,
			);
		}

		protected function parseStartTagToken() 
		{
			$tag_params = $this->lexer->tag;
			$tag_name = isset($tag_params['_name']) ? $tag_params['_name'] : null;
			
			if(!isset($this->tag_rules[$tag_name])):
				$this->stack[] = Array(
					self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
					self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
					self::BBCODE_STACK_TAG => false,
					self::BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			endif;

			if(isset($this->tag_rules[$tag_name]['allow_params'])):
				if($this->tag_rules[$tag_name]['allow_params'] === false):
					if(sizeof($tag_params['_params']) > 1):
						$this->stack[] = Array(
							self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
							self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
							self::BBCODE_STACK_TAG => false,
							self::BBCODE_STACK_CLASS => $this->current_class,
						);
						return;
					endif;
				endif;
			endif;

			$tag_rule = $this->tag_rules[$tag_name];
			$allow_in = is_array($tag_rule['allow_in']) ? $tag_rule['allow_in'] : Array($this->root_class);
			
			if(!in_array($this->current_class, $allow_in)):
				if(!$this->rewindToClass($allow_in)):
					$this->stack[] = Array(
						self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
						self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
						self::BBCODE_STACK_TAG => false,
						self::BBCODE_STACK_CLASS => $this->current_class,
					);
					return;
				endif;
			endif;

			$end_tag = isset($tag_rule['end_tag']) ? $tag_rule['end_tag'] : self::BBCODE_REQUIRED;

			if($end_tag == self::BBCODE_PROHIBIT):
				$this->processIsolatedTag($tag_name, $tag_params, $tag_rule);
				return;
			endif;

			if(!$this->doTag(self::BBCODE_CHECK, $tag_name, isset($tag_params['_default']) ? $tag_params['_default'] : null, $tag_params, "")):
				$this->stack[] = Array(
					self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
					self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
					self::BBCODE_STACK_TAG => false,
					self::BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			endif;

			if(isset($tag_rule['content']) && $tag_rule['content'] == self::BBCODE_VERBATIM):
				$this->processVerbatimTag($tag_name, $tag_params, $tag_rule);
				return;
			endif;

			if(isset($tag_rule['class']))
				$newclass = $tag_rule['class'];
			else
				$newclass = $this->root_class;
			
			$this->stack[] = Array(
				self::BBCODE_STACK_TOKEN => $this->lexer->token,
				self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
				self::BBCODE_STACK_TAG => $this->lexer->tag,
				self::BBCODE_STACK_CLASS => ($this->current_class = $newclass),
			);

			if(!isset($this->start_tags[$tag_name]))
				$this->start_tags[$tag_name] = Array(count($this->stack) - 1);
			else
				$this->start_tags[$tag_name][] = count($this->stack) - 1;
		}
		
		protected function parseEndTagToken() 
		{
			$tag_params = $this->lexer->tag;
			$tag_name = isset($tag_params['_name']) ? $tag_params['_name'] : null;
			$contents = $this->finishTag($tag_name);
			
			if($contents === false):
				if(isset($this->lost_start_tags[$tag_name]) && $this->lost_start_tags[$tag_name] > 0):
					$this->lost_start_tags[$tag_name]--;
				else:
					$this->stack[] = Array(
						self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
						self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
						self::BBCODE_STACK_TAG => false,
						self::BBCODE_STACK_CLASS => $this->current_class,
					);
				endif;
				return;
			endif;
			
			$start_tag_node = array_pop($this->stack);
			$start_tag_params = $start_tag_node[self::BBCODE_STACK_TAG];
			$this->computeCurrentClass();

			if(isset($this->tag_rules[$tag_name]) && isset($this->tag_rules[$tag_name]['before_tag'])):
				$this->cleanupWSByPoppingStack($this->tag_rules[$tag_name]['before_tag'], $this->stack);
			else:
				$this->cleanupWSByPoppingStack(null, $this->stack);
			endif;
			
			$start_tag_params['_endtag'] = $tag_params['_tag'];
			$start_tag_params['_hasend'] = true;
			$output = $this->doTag(
				self::BBCODE_OUTPUT,
				$tag_name,
				isset($start_tag_params['_default']) ? $start_tag_params['_default'] : null,
				$start_tag_params,
				$contents
			);

			if(isset($this->tag_rules[$tag_name]['after_endtag'])):
				$this->cleanupWSByEatingInput($this->tag_rules[$tag_name]['after_endtag']);
			else:
				$this->cleanupWSByEatingInput(null);
			endif;

			$this->stack[] = Array(
				self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
				self::BBCODE_STACK_TEXT => $output,
				self::BBCODE_STACK_TAG => false,
				self::BBCODE_STACK_CLASS => $this->current_class,
			);
		}

		public function parse($string) 
		{
			$this->lexer = new BBCodeLexer($string, $this->tag_marker);
			$old_output_limit = $this->output_limit;
		
			if($this->output_limit > 0):
				if(strlen($string) < $this->output_limit):
					$this->output_limit = 0;
				elseif($this->limit_precision > 0):
					$guess_length = $this->lexer->guessTextLength();
					if($guess_length < $this->output_limit * ($this->limit_precision + 1.0)):
						$this->output_limit = 0;
					endif;
				endif;
			endif;
			
			$this->stack = [];
			$this->start_tags = [];
			$this->lost_start_tags = [];
			$this->text_length = 0;
			$this->was_limited = false;

			if(strlen($this->pre_trim) > 0)
				$this->cleanupWSByEatingInput($this->pre_trim);

			$newline = $this->plain_mode ? "\n" : "<br>\n";

			while(true):
				if(($token_type = $this->lexer->nextToken()) == self::BBCODE_EOI):
					break;
				endif;

				switch($token_type):
					case self::BBCODE_TEXT:
						if($this->output_limit > 0 && $this->text_length + strlen($this->lexer->text) >= $this->output_limit):
							$text = $this->limitText($this->lexer->text, $this->output_limit - $this->text_length);
							if(strlen($text) > 0):
								$this->text_length += strlen($text);
								$this->stack[] = Array(
									self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
									self::BBCODE_STACK_TEXT => $this->fixupOutput($text),
									self::BBCODE_STACK_TAG => false,
									self::BBCODE_STACK_CLASS => $this->current_class,
								);
							endif;
							$this->doLimit();
							break 2;
						endif;
						$this->text_length += strlen($this->lexer->text);
						$this->stack[] = Array(
							self::BBCODE_STACK_TOKEN => self::BBCODE_TEXT,
							self::BBCODE_STACK_TEXT => $this->fixupOutput($this->lexer->text),
							self::BBCODE_STACK_TAG => false,
							self::BBCODE_STACK_CLASS => $this->current_class,
						);
						break;
					case self::BBCODE_WS:
						if($this->output_limit > 0 && $this->text_length + strlen($this->lexer->text) >= $this->output_limit):
							$this->doLimit();
							break 2;
						endif;
						$this->text_length += strlen($this->lexer->text);
						$this->stack[] = Array(
							self::BBCODE_STACK_TOKEN => self::BBCODE_WS,
							self::BBCODE_STACK_TEXT => $this->lexer->text,
							self::BBCODE_STACK_TAG => false,
							self::BBCODE_STACK_CLASS => $this->current_class,
						);
						break;
					case self::BBCODE_NL:
						if($this->ignore_newlines):
							if($this->output_limit > 0 && $this->text_length + 1 >= $this->output_limit):
								$this->doLimit();
								break 2;
							endif;
							$this->text_length += 1;
							$this->stack[] = Array(
								self::BBCODE_STACK_TOKEN => self::BBCODE_WS,
								self::BBCODE_STACK_TEXT => "\n",
								self::BBCODE_STACK_TAG => false,
								self::BBCODE_STACK_CLASS => $this->current_class,
							);
						else:
							$this->cleanupWSByPoppingStack("s", $this->stack);
							if($this->output_limit > 0 && $this->text_length + 1 >= $this->output_limit):
								$this->doLimit();
								break 2;
							endif;
							$this->text_length += 1;
							$this->stack[] = Array(
								self::BBCODE_STACK_TOKEN => self::BBCODE_NL,
								self::BBCODE_STACK_TEXT => $newline,
								self::BBCODE_STACK_TAG => false,
								self::BBCODE_STACK_CLASS => $this->current_class,
							);
							$this->cleanupWSByEatingInput("s");
						endif;
						break;
					case self::BBCODE_TAG:
						$this->parseStartTagToken();
						break;
					case self::BBCODE_ENDTAG:
						$this->parseEndTagToken();
						break;
					default:
						break;
				endswitch;
			endwhile;

			if (strlen($this->post_trim) > 0)
				$this->cleanupWSByPoppingStack($this->post_trim, $this->stack);

			$result = $this->generateOutput(0);
			$result = $this->collectTextReverse($result, count($result) - 1);
			$this->output_limit = $old_output_limit;

			if($this->plain_mode):
				$result = preg_replace("/[\\x00-\\x09\\x0B-\\x20]+/", " ", $result);
				$result = preg_replace("/(?:[\\x20]*\\n){2,}[\\x20]*/", "\n\n", $result);
				$result = trim($result);
			endif;

			return $result;
		}
	}

	class BBCodeLexer 
	{
		const BBCODE_LEXSTATE_TEXT = 0;
		const BBCODE_LEXSTATE_TAG = 1;

		public $token;
		public $text;
		public $tag;
		public $state;
		public $input;
		public $ptr;
		public $unget;
		public $verbatim;
		public $tagmarker;
		public $end_tagmarker;
		public $pat_main;
		public $pat_comment;
		public $pat_comment2;
		public $pat_wiki;

		public function __construct($string, $tagmarker = '[') 
		{
			$regex_beginmarkers = ['[' => '\[', '<' => '<', '{' => '\{', '(' => '\('];
			$regex_endmarkers = ['[' => '\]', '<' => '>', '{' => '\}', '(' => '\)'];
			$endmarkers = ['[' => ']', '<' => '>', '{' => '}', '(' => ')'];

			if(!isset($regex_endmarkers[$tagmarker])):
				$tagmarker = '[';
			endif;
			
			$e = $regex_endmarkers[$tagmarker];
			$b = $regex_beginmarkers[$tagmarker];
			$this->tagmarker = $tagmarker;
			$this->end_tagmarker = $endmarkers[$tagmarker];

			$this->pat_main = "/( "
				."{$b}"
				."(?! -- | ' | !-- | {$b}{$b} )"
				."(?: [^\\n\\r{$b}{$e}] | \\\" [^\\\"\\n\\r]* \\\" | \\' [^\\'\\n\\r]* \\' )*"
				."{$e}"
				."| {$b}{$b} (?: [^{$e}\\r\\n] | {$e}[^{$e}\\r\\n] ){1,256} {$e}{$e}"
				."| {$b} (?: -- | ' ) (?: [^{$e}\\n\\r]* ) {$e}"
				."| {$b}!-- (?: [^-] | -[^-] | --[^{$e}] )* --{$e}"
				."| -----+"
				."| \\x0D\\x0A | \\x0A\\x0D | \\x0D | \\x0A"
				."| [\\x00-\\x09\\x0B-\\x0C\\x0E-\\x20]+(?=[\\x0D\\x0A{$b}]|-----|$)"
				."| (?<=[\\x0D\\x0A{$e}]|-----|^)[\\x00-\\x09\\x0B-\\x0C\\x0E-\\x20]+"
				." )/Dx";

			$this->input = preg_split($this->pat_main, $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$this->pat_comment = "/^ {$b} (?: -- | ' ) /Dx";
			$this->pat_comment2 = "/^ {$b}!-- (?: [^-] | -[^-] | --[^{$e}] )* --{$e} $/Dx";
			$this->pat_wiki = "/^ {$b}{$b} ([^\\|]*) (?:\\|(.*))? {$e}{$e} $/Dx";
			$this->ptr = 0;
			$this->unget = false;
			$this->state = self::BBCODE_LEXSTATE_TEXT;
			$this->verbatim = false;
			$this->token = BBCode::BBCODE_EOI;
			$this->tag = false;
			$this->text = "";
		}

		public function guessTextLength() 
		{
			$length = 0;
			$ptr = 0;
			$state = self::BBCODE_LEXSTATE_TEXT;

			while($ptr < count($this->input)):
				$text = $this->input[$ptr++];

				if($state == self::BBCODE_LEXSTATE_TEXT):
					$state = self::BBCODE_LEXSTATE_TAG;
					$length += strlen($text);
				else:
					switch(ord(substr($this->text, 0, 1))):
						case 10:
						case 13:
							$state = self::BBCODE_LEXSTATE_TEXT;
							$length++;
							break;
						default:
							$state = self::BBCODE_LEXSTATE_TEXT;
							$length += strlen($text);
							break;
						case 40:
						case 60:
						case 91:
						case 123:
							$state = self::BBCODE_LEXSTATE_TEXT;
							break;
					endswitch;
				endif;
			endwhile;

			return $length;
		}

		public function nextToken() 
		{	
			if($this->unget):
				$this->unget = false;
				return $this->token;
			endif;

			while(true):
				if($this->ptr >= count($this->input)):
					$this->text = "";
					$this->tag = false;
					return $this->token = BBCode::BBCODE_EOI;
				endif;

				$this->text = preg_replace("/[\\x00-\\x08\\x0B-\\x0C\\x0E-\\x1F]/", "", $this->input[$this->ptr++]);

				if($this->verbatim):
					$this->tag = false;
					if($this->state == self::BBCODE_LEXSTATE_TEXT):
						$this->state = self::BBCODE_LEXSTATE_TAG;
						$token_type = BBCode::BBCODE_TEXT;
					else:
						$this->state = self::BBCODE_LEXSTATE_TEXT;
						switch(ord(substr($this->text, 0, 1))):
							case 10:
							case 13:
								$token_type = BBCode::BBCODE_NL;
								break;
							default:
								$token_type = BBCode::BBCODE_WS;
								break;
							case 45:
							case 40:
							case 60:
							case 91:
							case 123:
								$token_type = BBCode::BBCODE_TEXT;
								break;
						endswitch;
					endif;

					if(strlen($this->text) > 0):
						return $this->token = $token_type;
					endif;
				elseif($this->state == self::BBCODE_LEXSTATE_TEXT):
					$this->state = self::BBCODE_LEXSTATE_TAG;
					$this->tag = false;
					if(strlen($this->text) > 0):
						return $this->token = BBCode::BBCODE_TEXT;
					endif;
				else:
					switch(ord(substr($this->text, 0, 1))):
						case 10:
						case 13:
							$this->tag = false;
							$this->state = self::BBCODE_LEXSTATE_TEXT;
							return $this->token = BBCode::BBCODE_NL;
						case 45:
							if(preg_match("/^-----/", $this->text)):
								$this->tag = ['_name' => 'rule', '_endtag' => false, '_default' => ''];
								$this->state = self::BBCODE_LEXSTATE_TEXT;
								return $this->token = BBCode::BBCODE_TAG;
							else:
								$this->tag = false;
								$this->state = self::BBCODE_LEXSTATE_TEXT;
								if(strlen($this->text) > 0):
									return $this->token = BBCode::BBCODE_TEXT;
								endif;
								break;
							endif;
							break;
						default:
							$this->tag = false;
							$this->state = self::BBCODE_LEXSTATE_TEXT;
							return $this->token = BBCode::BBCODE_WS;
						case 40:
						case 60:
						case 91:
						case 123:
							if(preg_match($this->pat_comment, $this->text)):
								$this->state = self::BBCODE_LEXSTATE_TEXT;
								break;
							endif;
							
							if(preg_match($this->pat_comment2, $this->text)):
								$this->state = self::BBCODE_LEXSTATE_TEXT;
								break;
							endif;

							if(preg_match($this->pat_wiki, $this->text, $matches)):
								$matches += [1 => null, 2 => null];
								$this->tag = ['_name' => 'wiki', '_endtag' => false,
									'_default' => $matches[1], 'title' => $matches[2]];
								$this->state = self::BBCODE_LEXSTATE_TEXT;
								return $this->token = BBCode::BBCODE_TAG;
							endif;
							
							$this->tag = $this->decodeTag($this->text);
							$this->state = self::BBCODE_LEXSTATE_TEXT;
							return $this->token = ($this->tag['_end'] ? BBCode::BBCODE_ENDTAG : BBCode::BBCODE_TAG);
					endswitch;
				endif;
			endwhile;
		}

		public function ungetToken() 
		{
			if($this->token !== BBCode::BBCODE_EOI):
				$this->unget = true;
			endif;
		}
		
		public function peekToken() 
		{
			$result = $this->nextToken();
			if($this->token !== BBCode::BBCODE_EOI):
				$this->unget = true;
			endif;
			
			return $result;
		}
		
		public function saveState() 
		{
			return [
				'token' => $this->token,
				'text' => $this->text,
				'tag' => $this->tag,
				'state' => $this->state,
				'input' => $this->input,
				'ptr' => $this->ptr,
				'unget' => $this->unget,
				'verbatim' => $this->verbatim
			];
		}
		
		public function restoreState($state) 
		{
			if(!is_array($state)):
				return;
			endif;

			$state += [
				'token' => null, 'text' => null, 'tag' => null, 'state' => null, 'input' => null, 'ptr' => null,
				'unget' => null, 'verbatim' => null
			];

			$this->token = $state['token'];
			$this->text = $state['text'];
			$this->tag = $state['tag'];
			$this->state = $state['state'];
			$this->input = $state['input'];
			$this->ptr = $state['ptr'];
			$this->unget = $state['unget'];
			$this->verbatim = $state['verbatim'];
		}

		protected function stripQuotes($string) 
		{
			if(strlen($string) > 1):
				$first = substr($string, 0, 1);
				$last = substr($string, -1);
				if($first === $last && ($first === '"' || $first === "'")):
					return substr($string, 1, -1);
				endif;
			endif;
			
			return $string;
		}

		protected function classifyPiece($ptr, $pieces) 
		{
			if($ptr >= count($pieces)):
				return -1;
			endif;
			
			$piece = $pieces[$ptr];
			
			if($piece == '='):
				return '=';
			elseif(preg_match("/^[\\'\\\"]/", $piece)):
				return '"';
			elseif(preg_match("/^[\\x00-\\x20]+$/", $piece)):
				return ' ';
			else:
				return 'A';
			endif;
		}

		protected function decodeTag($tag) 
		{
			$result = ['_tag' => $tag, '_endtag' => '', '_name' => '', '_hasend' => false, '_end' => false, '_default' => false];
			$tag = substr($tag, 1, strlen($tag) - 2);
			$ch = ord(substr($tag, 0, 1));
			
			if($ch >= 0 && $ch <= 32):
				return $result;
			endif;
			
			$pieces = preg_split(
				"/(\\\"[^\\\"]+\\\"|\\'[^\\']+\\'|=|[\\x00-\\x20]+)/",
				$tag,
				-1,
				PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
			);
			
			$ptr = 0;

			if(count($pieces) < 1):
				return $result;
			endif;
			
			if(!empty($pieces[$ptr]) && substr($pieces[$ptr], 0, 1) === '/'):
				$result['_name'] = strtolower(substr($pieces[$ptr++], 1));
				$result['_end'] = true;
			else:
				$result['_name'] = strtolower($pieces[$ptr++]);
				$result['_end'] = false;
			endif;

			while(($type = $this->classifyPiece($ptr, $pieces)) == ' '):
				$ptr++;
			endwhile;

			$params = [];

			if($type != '='):
				$result['_default'] = false;
				$params[] = ['key' => '', 'value' => ''];
			else:
				$ptr++;

				while(($type = $this->classifyPiece($ptr, $pieces)) == ' '):
					$ptr++;
				endwhile;

				if($type == "\""):
					$value = $this->stripQuotes($pieces[$ptr++]);
				else:
					$after_space = false;
					$start = $ptr;
					while(($type = $this->classifyPiece($ptr, $pieces)) != -1):
						if($type == ' '):
							$after_space = true;
						endif;
						if($type == '=' && $after_space):
							break;
						endif;
						$ptr++;
					endwhile;
					
					if($type == -1):
						$ptr--;
					endif;
					
					if($type == '='):
						$ptr--;
						while($ptr > $start && $this->classifyPiece($ptr, $pieces) == ' '):
							$ptr--;
						endwhile;
						
						while($ptr > $start && $this->classifyPiece($ptr, $pieces) != ' '):
							$ptr--;
						endwhile;
					endif;

					$value = "";
					for(; $start <= $ptr; $start++):
						if($this->classifyPiece($start, $pieces) == ' '):
							$value .= " ";
						else:
							$value .= $this->stripQuotes($pieces[$start]);
						endif;
					endfor;
					
					$value = trim($value);
					$ptr++;
				endif;

				$result['_default'] = $value;
				$params[] = ['key' => '', 'value' => $value];
			endif;

			while(($type = $this->classifyPiece($ptr, $pieces)) != -1):
				while($type === ' '):
					$ptr++;
					$type = $this->classifyPiece($ptr, $pieces);
				endwhile;

				if($type === 'A' || $type === '"'):
					if(isset($pieces[$ptr])):
						$key = strtolower($this->stripQuotes($pieces[$ptr]));
					else:
						$key = '';
					endif;
					$ptr++;
				elseif($type === '='):
					$ptr++;
					continue;
				elseif($type === -1):
					break;
				endif;

				while(($type = $this->classifyPiece($ptr, $pieces)) == ' '):
					$ptr++;
				endwhile;

				if($type !== '='):
					$value = $this->stripQuotes($key);
				else:
					$ptr++;
					while(($type = $this->classifyPiece($ptr, $pieces)) == ' '):
						$ptr++;
					endwhile;

					if($type === '"'):
						$value = $this->stripQuotes($pieces[$ptr++]);
					elseif($type !== -1):
						$value = $pieces[$ptr++];
						while(($type = $this->classifyPiece($ptr, $pieces)) != -1
							&& $type != ' '):
							$value .= $pieces[$ptr++];
						endwhile;
					else:
						$value = "";
					endif;
				endif;
				
				if(substr($key, 0, 1) !== '_'):
					$result[$key] = $value;
				endif;

				$params[] = ['key' => $key, 'value' => $value];
			endwhile;

			$result['_params'] = $params;

			return $result;
		}
	}

	class BBCodeLibrary 
	{
		public $default_smileys = [
			':)' => 'smile.gif', ':-)' => 'smile.gif',
			'=)' => 'smile.gif', '=-)' => 'smile.gif',
			':(' => 'frown.gif', ':-(' => 'frown.gif',
			'=(' => 'frown.gif', '=-(' => 'frown.gif',
			':D' => 'bigsmile.gif', ':-D' => 'bigsmile.gif',
			'=D' => 'bigsmile.gif', '=-D' => 'bigsmile.gif',
			'>:(' => 'angry.gif', '>:-(' => 'angry.gif',
			'>=(' => 'angry.gif', '>=-(' => 'angry.gif',
			'D:' => 'angry.gif', 'D-:' => 'angry.gif',
			'D=' => 'angry.gif', 'D-=' => 'angry.gif',
			'>:)' => 'evil.gif', '>:-)' => 'evil.gif',
			'>=)' => 'evil.gif', '>=-)' => 'evil.gif',
			'>:D' => 'evil.gif', '>:-D' => 'evil.gif',
			'>=D' => 'evil.gif', '>=-D' => 'evil.gif',
			'>;)' => 'sneaky.gif', '>;-)' => 'sneaky.gif',
			'>;D' => 'sneaky.gif', '>;-D' => 'sneaky.gif',
			'O:)' => 'saint.gif', 'O:-)' => 'saint.gif',
			'O=)' => 'saint.gif', 'O=-)' => 'saint.gif',
			':O' => 'surprise.gif', ':-O' => 'surprise.gif',
			'=O' => 'surprise.gif', '=-O' => 'surprise.gif',
			':?' => 'confuse.gif', ':-?' => 'confuse.gif',
			'=?' => 'confuse.gif', '=-?' => 'confuse.gif',
			':s' => 'worry.gif', ':-S' => 'worry.gif',
			'=s' => 'worry.gif', '=-S' => 'worry.gif',
			':|' => 'neutral.gif', ':-|' => 'neutral.gif',
			'=|' => 'neutral.gif', '=-|' => 'neutral.gif',
			':I' => 'neutral.gif', ':-I' => 'neutral.gif',
			'=I' => 'neutral.gif', '=-I' => 'neutral.gif',
			':/' => 'irritated.gif', ':-/' => 'irritated.gif',
			'=/' => 'irritated.gif', '=-/' => 'irritated.gif',
			':\\' => 'irritated.gif', ':-\\' => 'irritated.gif',
			'=\\' => 'irritated.gif', '=-\\' => 'irritated.gif',
			':P' => 'tongue.gif', ':-P' => 'tongue.gif',
			'=P' => 'tongue.gif', '=-P' => 'tongue.gif',
			'X-P' => 'tongue.gif',
			'8)' => 'bigeyes.gif', '8-)' => 'bigeyes.gif',
			'B)' => 'cool.gif', 'B-)' => 'cool.gif',
			';)' => 'wink.gif', ';-)' => 'wink.gif',
			'^_^' => 'anime.gif', '^^;' => 'sweatdrop.gif',
			'>_>' => 'lookright.gif', '>.>' => 'lookright.gif',
			'<_<' => 'lookleft.gif', '<.<' => 'lookleft.gif',
			'XD' => 'laugh.gif', 'X-D' => 'laugh.gif',
			';D' => 'bigwink.gif', ';-D' => 'bigwink.gif',
			':3' => 'smile3.gif', ':-3' => 'smile3.gif',
			'=3' => 'smile3.gif', '=-3' => 'smile3.gif',
			';3' => 'wink3.gif', ';-3' => 'wink3.gif',
			'<g>' => 'teeth.gif', '<G>' => 'teeth.gif',
			'o.O' => 'boggle.gif', 'O.o' => 'boggle.gif',
			':blue:' => 'blue.gif',
			':zzz:' => 'sleepy.gif',
			'<3' => 'heart.gif',
			':star:' => 'star.gif',
		];

		public $default_tag_rules = [
			'b' => [
				'simple_start' => "<b>",
				'simple_end' => "</b>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'plain_start' => "<b>",
				'plain_end' => "</b>",
				'allow_params' => false,
			],
			'i' => [
				'simple_start' => "<i>",
				'simple_end' => "</i>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'plain_start' => "<i>",
				'plain_end' => "</i>",
				'allow_params' => false,
			],
			'u' => [
				'simple_start' => "<u>",
				'simple_end' => "</u>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'plain_start' => "<u>",
				'plain_end' => "</u>",
				'allow_params' => false,
			],
			's' => [
				'simple_start' => "<strike>",
				'simple_end' => "</strike>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'plain_start' => "<i>",
				'plain_end' => "</i>",
				'allow_params' => false,
			],
			'font' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'allow' => ['_default' => '/^[a-zA-Z0-9._ -]+$/'],
				'method' => 'doFont',
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
			],
			'color' => [
				'mode' => BBCode::BBCODE_MODE_ENHANCED,
				'allow' => ['_default' => '/^#?[a-zA-Z0-9._ -]+$/'],
				'template' => '<span style="color:{$_default/tw}">{$_content/v}</span>',
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
			],
			'size' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'allow' => ['_default' => '/^[0-9.]+$/D'],
				'method' => 'doSize',
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
			],
			'sup' => [
				'simple_start' => "<sup>",
				'simple_end' => "</sup>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'allow_params' => false,
			],
			'sub' => [
				'simple_start' => "<sub>",
				'simple_end' => "</sub>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'allow_params' => false,
			],
			'spoiler' => [
				'simple_start' => "<span class=\"bbcode_spoiler\">",
				'simple_end' => "</span>",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
			],
			'acronym' => [
				'mode' => BBCode::BBCODE_MODE_ENHANCED,
				'template' => '<span class="bbcode_acronym" title="{$_default/e}">{$_content/v}</span>',
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
			],
			'url' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => 'doURL',
				'class' => 'link',
				'allow_in' => ['listitem', 'block', 'columns', 'inline'],
				'content' => BBCode::BBCODE_REQUIRED,
				'plain_start' => "<a href=\"{\$link}\">",
				'plain_end' => "</a>",
				'plain_content' => ['_content', '_default'],
				'plain_link' => ['_default', '_content'],
			],
			'email' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => 'doEmail',
				'class' => 'link',
				'allow_in' => ['listitem', 'block', 'columns', 'inline'],
				'content' => BBCode::BBCODE_REQUIRED,
				'plain_start' => "<a href=\"mailto:{\$link}\">",
				'plain_end' => "</a>",
				'plain_content' => ['_content', '_default'],
				'plain_link' => ['_default', '_content'],
			],
			'wiki' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => "doWiki",
				'class' => 'link',
				'allow_in' => ['listitem', 'block', 'columns', 'inline'],
				'end_tag' => BBCode::BBCODE_PROHIBIT,
				'content' => BBCode::BBCODE_PROHIBIT,
				'plain_start' => "<b>[",
				'plain_end' => "]</b>",
				'plain_content' => ['title', '_default'],
				'plain_link' => ['_default', '_content'],
			],
			'img' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => "doImage",
				'class' => 'image',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'end_tag' => BBCode::BBCODE_REQUIRED,
				'content' => BBCode::BBCODE_OPTIONAL,
				'plain_start' => "[image]",
				'plain_content' => [],
			],
			'rule' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => "doRule",
				'class' => 'block',
				'allow_in' => ['listitem', 'block', 'columns'],
				'end_tag' => BBCode::BBCODE_PROHIBIT,
				'content' => BBCode::BBCODE_PROHIBIT,
				'before_tag' => "sns",
				'after_tag' => "sns",
				'plain_start' => "\n-----\n",
				'plain_end' => "",
				'plain_content' => [],
			],
			'br' => [
				'mode' => BBCode::BBCODE_MODE_SIMPLE,
				'simple_start' => "<br>\n",
				'simple_end' => "",
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
				'end_tag' => BBCode::BBCODE_PROHIBIT,
				'content' => BBCode::BBCODE_PROHIBIT,
				'before_tag' => "s",
				'after_tag' => "s",
				'plain_start' => "\n",
				'plain_end' => "",
				'plain_content' => [],
			],
			'left' => [
				'simple_start' => "\n<div class=\"bbcode_left\" style=\"text-align:left\">\n",
				'simple_end' => "\n</div>\n",
				'allow_in' => ['listitem', 'block', 'columns'],
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "\n",
			],
			'right' => [
				'simple_start' => "\n<div class=\"bbcode_right\" style=\"text-align:right\">\n",
				'simple_end' => "\n</div>\n",
				'allow_in' => ['listitem', 'block', 'columns'],
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "\n",
			],
			'center' => [
				'simple_start' => "\n<div class=\"bbcode_center\" style=\"text-align:center\">\n",
				'simple_end' => "\n</div>\n",
				'allow_in' => ['listitem', 'block', 'columns'],
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "\n",
			],
			'rtl' => [
				'simple_start' => '<div style="direction:rtl;">',
				'simple_end' => '</div>',
				'class' => 'inline',
				'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link'],
			],
			'indent' => [
				'simple_start' => "\n<div class=\"bbcode_indent\" style=\"margin-left:4em\">\n",
				'simple_end' => "\n</div>\n",
				'allow_in' => ['listitem', 'block', 'columns'],
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "\n",
			],
			'columns' => [
				'simple_start' => "\n<table class=\"bbcode_columns\"><tbody><tr><td class=\"bbcode_column bbcode_firstcolumn\">\n",
				'simple_end' => "\n</td></tr></tbody></table>\n",
				'class' => 'columns',
				'allow_in' => ['listitem', 'block', 'columns'],
				'end_tag' => BBCode::BBCODE_REQUIRED,
				'content' => BBCode::BBCODE_REQUIRED,
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "\n",
			],
			'nextcol' => [
				'simple_start' => "\n</td><td class=\"bbcode_column\">\n",
				'class' => 'nextcol',
				'allow_in' => ['columns'],
				'end_tag' => BBCode::BBCODE_PROHIBIT,
				'content' => BBCode::BBCODE_PROHIBIT,
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "",
			],
			'code' => [
				'mode' => BBCode::BBCODE_MODE_ENHANCED,
				'template' => "\n<div class=\"bbcode_code\">\n<div class=\"bbcode_code_head\">Code:</div>\n<div class=\"bbcode_code_body\" style=\"white-space:pre\">{\$_content/v}</div>\n</div>\n",
				'class' => 'code',
				'allow_in' => ['listitem', 'block', 'columns'],
				'content' => BBCode::BBCODE_VERBATIM,
				'before_tag' => "sns",
				'after_tag' => "sn",
				'before_endtag' => "sn",
				'after_endtag' => "sns",
				'plain_start' => "\n<b>Code:</b>\n",
				'plain_end' => "\n",
			],
			'quote' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => "doQuote",
				'allow_in' => ['listitem', 'block', 'columns'],
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n<b>Quote:</b>\n",
				'plain_end' => "\n",
			],
			'list' => [
				'mode' => BBCode::BBCODE_MODE_LIBRARY,
				'method' => 'doList',
				'class' => 'list',
				'allow_in' => ['listitem', 'block', 'columns'],
				'before_tag' => "sns",
				'after_tag' => "sns",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n",
				'plain_end' => "\n",
			],
			'*' => [
				'simple_start' => "<li>",
				'simple_end' => "</li>\n",
				'class' => 'listitem',
				'allow_in' => ['list'],
				'end_tag' => BBCode::BBCODE_OPTIONAL,
				'before_tag' => "s",
				'after_tag' => "s",
				'before_endtag' => "sns",
				'after_endtag' => "sns",
				'plain_start' => "\n * ",
				'plain_end' => "\n",
			],
		];

		protected $imageExtensions = ['gif', 'jpg', 'jpeg', 'png', 'svg'];

		public function doURL(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			if($action == BBCode::BBCODE_CHECK):
				return true;
			endif;

			$url = is_string($default)
				? $default
				: $bbcode->unHTMLEncode(strip_tags($content));

			if($bbcode->isValidURL($url)):
				if($bbcode->getURLTargetable() !== false && isset($params['target'])):
					$target = ' target="'.htmlspecialchars($params['target']).'"';
				else:
					$target = '';
				endif;

				if($bbcode->getURLTarget() !== false && empty($target)):
					$target = ' target="'.htmlspecialchars($bbcode->getURLTarget()).'"';
				endif;
				
				$content = preg_replace('/^\\<a [^\\>]*\\>(.*?)<\\/a>$/', "\\1", $content);

				return $bbcode->fillTemplate($bbcode->getURLTemplate(), array("url" => $url, "target" => $target, "content" => $content));
			else:
				return htmlspecialchars($params['_tag']).$content.htmlspecialchars($params['_endtag']);
			endif;
		}
		
		public function doEmail(BBCode $bbcode, $action, $name, $default, $params, $content)
		{
			if($action == BBCode::BBCODE_CHECK):
				return true;
			endif;

			$email = is_string($default)
				? $default
				: $bbcode->unHTMLEncode(strip_tags($content));

			if($bbcode->isValidEmail($email)):
				return $bbcode->fillTemplate($bbcode->getEmailTemplate(), array("email" => $email, "content" => $content));
			else:
				return htmlspecialchars($params['_tag']).$content.htmlspecialchars($params['_endtag']);
			endif;
		}

		public function doSize(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			switch($default):
				case '0':
					$size = '.5em';
					break;
				case '1':
					$size = '.67em';
					break;
				case '2':
					$size = '.83em';
					break;
				case '3':
					$size = '1.0em';
					break;
				case '4':
					$size = '1.17em';
					break;
				case '5':
					$size = '1.5em';
					break;
				case '6':
					$size = '2.0em';
					break;
				case '7':
					$size = '2.5em';
					break;
				default:
					$size = (int)$default;
					if($size < 11 || $size > 48):
						$size = '1.0em';
					else:
						$size .= 'px';
					endif;
					break;
			endswitch;
			
			return '<span style="font-size:'.$size.'">'.$content.'</span>';
		}

		public function doFont(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			$fonts = explode(",", $default);
			$result = "";
			$special_fonts = [
				'serif' => 'serif',
				'sans-serif' => 'sans-serif',
				'sans serif' => 'sans-serif',
				'sansserif' => 'sans-serif',
				'sans' => 'sans-serif',
				'cursive' => 'cursive',
				'fantasy' => 'fantasy',
				'monospace' => 'monospace',
				'mono' => 'monospace',
			];
			
			foreach($fonts as $font):
				$font = trim($font);
				if(isset($special_fonts[$font])):
					if(strlen($result) > 0):
						$result .= ",";
					endif;
					$result .= $special_fonts[$font];
				elseif(strlen($font) > 0):
					if(strlen($result) > 0):
						$result .= ",";
					endif;
					$result .= "'$font'";
				endif;
			endforeach;
			
			return "<span style=\"font-family:$result\">$content</span>";
		}

		public function doWiki(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			$name = $bbcode->wikify($default);

			if($action == BBCode::BBCODE_CHECK):
				return strlen($name) > 0;
			endif;

			if(isset($params['title']) && strlen(trim($params['title']))):
				$title = trim($params['title']);
			else:
				$title = trim($default);
			endif;

			$wikiURL = $bbcode->getWikiURL();
			
			return $bbcode->fillTemplate($bbcode->getWikiURLTemplate(), array("wikiURL" => $wikiURL, "name" => $name, "title" => $title));
		}

		public function doImage(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			if($action == BBCode::BBCODE_CHECK):
				return true;
			endif;

			$content = trim($bbcode->unHTMLEncode(strip_tags($content)));
			
			if(empty($content) && $default):
				$content = $default;
			endif;

			$urlParts = parse_url($content);

			if(is_array($urlParts)):
				if(!empty($urlParts['path']) &&
					empty($urlParts['scheme']) &&
					!preg_match('`^\.{0,2}/`', $urlParts['path']) &&
					in_array(pathinfo($urlParts['path'], PATHINFO_EXTENSION), $this->imageExtensions)
				):
					$localImgURL = $bbcode->getLocalImgURL();
					return "<img src=\""
					.htmlspecialchars((empty($localImgURL) ? '' : $localImgURL.'/').ltrim($urlParts['path'], '/')).'" alt="'
					.htmlspecialchars(basename($content)).'" class="bbcode_img" />';
				elseif($bbcode->isValidURL($content, false)):
					return '<img src="'.htmlspecialchars($content).'" alt="'
					.htmlspecialchars(basename($content)).'" class="bbcode_img" />';
				endif;
			endif;
			
			return htmlspecialchars($params['_tag']).htmlspecialchars($content).htmlspecialchars($params['_endtag']);
		}

		public function doRule(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			if($action == BBCode::BBCODE_CHECK):
				return true;
			else:
				return $bbcode->getRuleHTML();
			endif;
		}

		public function doQuote(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			if($action == BBCode::BBCODE_CHECK):
				return true;
			endif;

			if(isset($params['name'])):
				$title = htmlspecialchars(trim($params['name']))." wrote";
				if(isset($params['date'])):
					$title .= " on ".htmlspecialchars(trim($params['date']));
				endif;
				$title .= ":";
				if(isset($params['url'])):
					$url = trim($params['url']);
					if($bbcode->isValidURL($url)):
						$title = "<a href=\"".htmlspecialchars($params['url'])."\">".$title."</a>";
					endif;
				endif;
			elseif(!is_string($default)):
				$title = "Quote:";
			else:
				$title = htmlspecialchars(trim($default))." wrote:";
			endif;

			return $bbcode->fillTemplate($bbcode->getQuoteTemplate(), array("title" => $title, "content" => $content));
		}

		public function doList(BBCode $bbcode, $action, $name, $default, $params, $content) 
		{
			$listStyles = [
				'1' => 'decimal',
				'01' => 'decimal-leading-zero',
				'i' => 'lower-roman',
				'I' => 'upper-roman',
				'a' => 'lower-alpha',
				'A' => 'upper-alpha',
			];
			$ciListStyles = [
				'circle' => 'circle',
				'disc' => 'disc',
				'square' => 'square',
				'greek' => 'lower-greek',
				'armenian' => 'armenian',
				'georgian' => 'georgian',
			];
			$ulTypes = [
				'circle' => 'circle',
				'disc' => 'disc',
				'square' => 'square',
			];

			$default = trim($default);

			if($action == BBCode::BBCODE_CHECK):
				if(!is_string($default) || strlen($default) == ""):
					return true;
				elseif(isset($listStyles[$default])):
					return true;
				elseif (isset($ciListStyles[strtolower($default)])):
					return true;
				else:
					return false;
				endif;
			endif;
			
			if(!is_string($default) || strlen($default) == ""):
				$elem = 'ul';
				$type = '';
			elseif($default == '1'):
				$elem = 'ol';
				$type = '';
			elseif(isset($listStyles[$default])):
				$elem = 'ol';
				$type = $listStyles[$default];
			else:
				$default = strtolower($default);
				if(isset($ulTypes[$default])):
					$elem = 'ul';
					$type = $ulTypes[$default];
				elseif(isset($ciListStyles[$default])):
					$elem = 'ol';
					$type = $ciListStyles[$default];
				endif;
			endif;

			if(strlen($type)):
				return "\n<$elem class=\"bbcode_list\" style=\"list-style-type:$type\">\n$content</$elem>\n";
			else:
				return "\n<$elem class=\"bbcode_list\">\n$content</$elem>\n";
			endif;
		}
	}

	class EmailAddressValidator 
	{
		function check_email_address($strEmailAddress) 
		{
			if(preg_match('/[\x00-\x1F\x7F-\xFF]/', $strEmailAddress)):
				return false;
			endif;

			$intAtSymbol = strrpos($strEmailAddress, '@');
			
			if($intAtSymbol === false):
				return false;
			endif;
			
			$arrEmailAddress[0] = substr($strEmailAddress, 0, $intAtSymbol);
			$arrEmailAddress[1] = substr($strEmailAddress, $intAtSymbol + 1);
			$arrTempAddress[0] = preg_replace('/"[^"]+"/'
				, ''
				, $arrEmailAddress[0]);
			$arrTempAddress[1] = $arrEmailAddress[1];
			$strTempAddress = $arrTempAddress[0].$arrTempAddress[1];
			
			if(strrpos($strTempAddress, '@') !== false):
				return false;
			endif;

			if(!$this->check_local_portion($arrEmailAddress[0])):
				return false;
			endif;

			if(!$this->check_domain_portion($arrEmailAddress[1])):
				return false;
			endif;

			return true;
		}

		function check_local_portion($strLocalPortion) 
		{
			if(!$this->check_text_length($strLocalPortion, 1, 64)):
				return false;
			endif;

			$arrLocalPortion = explode('.', $strLocalPortion);
			for($i = 0, $max = sizeof($arrLocalPortion); $i < $max; $i++):
				if(!preg_match('.^('
					.'([A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]'
					.'[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]{0,63})'
					.'|'
					.'("[^\\\"]{0,62}")'
					.')$.'
					, $arrLocalPortion[$i])
				):
					return false;
				endif;
			endfor;
			
			return true;
		}

		function check_domain_portion($strDomainPortion) 
		{
			if(!$this->check_text_length($strDomainPortion, 1, 255)):
				return false;
			endif;
			
			if(preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
					.'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/'
					, $strDomainPortion) ||
				preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
					.'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/'
					, $strDomainPortion)
			):
				return true;
			else:
				$arrDomainPortion = explode('.', $strDomainPortion);
				if(sizeof($arrDomainPortion) < 2):
					return false;
				endif;
				for($i = 0, $max = sizeof($arrDomainPortion); $i < $max; $i++):
					if(!$this->check_text_length($arrDomainPortion[$i], 1, 63)):
						return false;
					endif;
					if(!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|'
						.'([A-Za-z0-9]+))$/', $arrDomainPortion[$i])
					):
						return false;
					endif;
				endfor;
			endif;
			
			return true;
		}

		function check_text_length($strText, $intMinimum, $intMaximum) 
		{
			$intTextLength = strlen($strText);
			if(($intTextLength < $intMinimum) || ($intTextLength > $intMaximum)):
				return false;
			else:
				return true;
			endif;
		}
	}
