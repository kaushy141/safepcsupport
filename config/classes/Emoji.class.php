<?php
class Emoji{
	private static $iconPath = 'img/system/emoji/';
	private static $icons = array(
		'😎' => array('img' => 'cool.gif', 'text' => ''),
		'😔' => array('img' => 'dissapointment.png', 'text' => ''),
		'😀' => array('img' => 'grinning.png', 'text' => ''),
		'🙂' => array('img' => 'happy.png', 'text' => ''),
		'😡' => array('img' => 'angry.png', 'text' => ''),
		'☺️' => array('img' => 'happy-hand.png', 'text' => ''),
		'😍' => array('img' => 'in-love.png', 'text' => ''),
		'🥰' => array('img' => 'in-love-smile.png', 'text' => ''),
		'🥳' => array('img' => 'party.png', 'text' => ''),
		'😔' => array('img' => 'sad.png', 'text' => '😔😢😪😓😥'),
		'😭' => array('img' => 'sad.gif', 'text' => ''),
		'😩' => array('img' => 'sad-2.png', 'text' => '😫😫😖☹️🙁😕😟'),
		'😨' => array('img' => 'scared.png', 'text' => ''),
		'🙂' => array('img' => 'smile.png', 'text' => ''),
		'😇' => array('img' => 'laugh.gif', 'text' => ''),
		'🤔' => array('img' => 'thinking.gif', 'text' => ''),
		'👻' => array('img' => 'ghost.png', 'text' => ''),
		'😠' => array('img' => 'angry-2.png', 'text' => ''),
		'😋' => array('img' => 'taste.png', 'text' => ''),
		'🤒' => array('img' => 'sick.png', 'text' => ''),
		'🥱' => array('img' => 'sleep.gif', 'text' => '😪😩😴'),
		'👍' => array('img' => 'like.png', 'text' => ''),
		'🌟' => array('img' => 'star.png', 'text' => '⭐'),
		'❗' => array('img' => 'alert.png', 'text' => ''),
		'✔️' => array('img' => 'check.png', 'text' => ''),
		'🆗' => array('img' => 'ok.png', 'text' => ''),
		'👌' => array('img' => 'okay.png', 'text' => ''),
		'‼️' => array('img' => 'warn.png', 'text' => ''),
		'⚠️' => array('img' => 'warning.png', 'text' => ''),
		'🟩' => array('img' => 'verified.png', 'text' => ''),
		'🥑' => array('img' => 'avocado.png', 'text' => ''),
		'🍌' => array('img' => 'bananas.png', 'text' => ''),
		'🍋' => array('img' => 'lemon.png', 'text' => ''),
		'🍎' => array('img' => 'apple.png', 'text' => ''),
		'🍍' => array('img' => 'pineapple.png', 'text' => ''),
		'🍊' => array('img' => 'orange-juice.png', 'text' => ''),
		'🍉' => array('img' => 'watermelon.png', 'text' => ''),
		'🍦' => array('img' => 'ice-cream.png', 'text' => ''),
		'📇' => array('img' => 'ice-cream-cart.png', 'text' => ''),
		'🍧' => array('img' => 'ice-cream-cone.png', 'text' => ''),
		'🍹' => array('img' => 'drink.png', 'text' => ''),
		'🍵' => array('img' => 'cup.png', 'text' => ''),
		'🐷' => array('img' => 'pig.png', 'text' => ''),
		'🐱' => array('img' => 'cat.png', 'text' => ''),
		'🐈' => array('img' => 'cat-white.png', 'text' => ''),
		'💃' => array('img' => 'doll.png', 'text' => ''),
		'👑' => array('img' => 'crown.png', 'text' => ''),
		'👸' => array('img' => 'crown-queen.png', 'text' => ''),
		'🦊' => array('img' => 'fox.png', 'text' => ''),
		'🐸' => array('img' => 'frog.png', 'text' => ''),
		'🦁' => array('img' => 'lion.png', 'text' => ''),
		'🐧' => array('img' => 'penguin.png', 'text' => ''),
		'🦉' => array('img' => 'owl.png', 'text' => ''),
		'🐙' => array('img' => 'octopus.png', 'text' => ''),
		'🧸' => array('img' => 'teddy-bear.png', 'text' => ''),
		'🐢' => array('img' => 'turtle.png', 'text' => ''),
		'🔋' => array('img' => 'battery.png', 'text' => ''),
		'📞' => array('img' => 'call.png', 'text' => ''),
		'⚡' => array('img' => 'flash.png', 'text' => ''),
		'📱' => array('img' => 'responsive-design.png', 'text' => ''),
		'🖥️' => array('img' => 'monitor.png', 'text' => ''),		
		'☎️' => array('img' => 'telephone.png', 'text' => ''),
		'🏆' => array('img' => 'trophy.png', 'text' => '')
	);	
	//7699 7700
	
	public static function getEmojiInput(){
		global $app;
		$html = "";
		foreach(self::$icons as $key => $_icon){
			$html .= "<img class=\"emoji-input-icon emojiinput\" alt=\"".$key."\" src='".$app->basePath(self::$iconPath).$_icon['img']."' />";
		}
		return $html;
	}
	
	public static function mapIcon($string){
		global $app;
		if(strlen($string) != strlen(utf8_decode($string)))
		{
			foreach(self::$icons as $key => $_icon){
				$string = str_replace($key, "<img class=\"emoji-input-icon\" alt=\"".$key."\" src='".$app->basePath(self::$iconPath).$_icon['img']."' />", $string);
			}
		}
		return $string;
	}
}
?>