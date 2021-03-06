<?php
class Emoji{
	private static $iconPath = 'img/system/emoji/';
	private static $icons = array(
		'π' => array('img' => 'cool.gif', 'text' => ''),
		'π' => array('img' => 'dissapointment.png', 'text' => ''),
		'π' => array('img' => 'grinning.png', 'text' => ''),
		'π' => array('img' => 'happy.png', 'text' => ''),
		'π‘' => array('img' => 'angry.png', 'text' => ''),
		'βΊοΈ' => array('img' => 'happy-hand.png', 'text' => ''),
		'π' => array('img' => 'in-love.png', 'text' => ''),
		'π₯°' => array('img' => 'in-love-smile.png', 'text' => ''),
		'π₯³' => array('img' => 'party.png', 'text' => ''),
		'π' => array('img' => 'sad.png', 'text' => 'ππ’πͺππ₯'),
		'π­' => array('img' => 'sad.gif', 'text' => ''),
		'π©' => array('img' => 'sad-2.png', 'text' => 'π«π«πβΉοΈπππ'),
		'π¨' => array('img' => 'scared.png', 'text' => ''),
		'π' => array('img' => 'smile.png', 'text' => ''),
		'π' => array('img' => 'laugh.gif', 'text' => ''),
		'π€' => array('img' => 'thinking.gif', 'text' => ''),
		'π»' => array('img' => 'ghost.png', 'text' => ''),
		'π ' => array('img' => 'angry-2.png', 'text' => ''),
		'π' => array('img' => 'taste.png', 'text' => ''),
		'π€' => array('img' => 'sick.png', 'text' => ''),
		'π₯±' => array('img' => 'sleep.gif', 'text' => 'πͺπ©π΄'),
		'π' => array('img' => 'like.png', 'text' => ''),
		'π' => array('img' => 'star.png', 'text' => 'β­'),
		'β' => array('img' => 'alert.png', 'text' => ''),
		'βοΈ' => array('img' => 'check.png', 'text' => ''),
		'π' => array('img' => 'ok.png', 'text' => ''),
		'π' => array('img' => 'okay.png', 'text' => ''),
		'βΌοΈ' => array('img' => 'warn.png', 'text' => ''),
		'β οΈ' => array('img' => 'warning.png', 'text' => ''),
		'π©' => array('img' => 'verified.png', 'text' => ''),
		'π₯' => array('img' => 'avocado.png', 'text' => ''),
		'π' => array('img' => 'bananas.png', 'text' => ''),
		'π' => array('img' => 'lemon.png', 'text' => ''),
		'π' => array('img' => 'apple.png', 'text' => ''),
		'π' => array('img' => 'pineapple.png', 'text' => ''),
		'π' => array('img' => 'orange-juice.png', 'text' => ''),
		'π' => array('img' => 'watermelon.png', 'text' => ''),
		'π¦' => array('img' => 'ice-cream.png', 'text' => ''),
		'π' => array('img' => 'ice-cream-cart.png', 'text' => ''),
		'π§' => array('img' => 'ice-cream-cone.png', 'text' => ''),
		'πΉ' => array('img' => 'drink.png', 'text' => ''),
		'π΅' => array('img' => 'cup.png', 'text' => ''),
		'π·' => array('img' => 'pig.png', 'text' => ''),
		'π±' => array('img' => 'cat.png', 'text' => ''),
		'π' => array('img' => 'cat-white.png', 'text' => ''),
		'π' => array('img' => 'doll.png', 'text' => ''),
		'π' => array('img' => 'crown.png', 'text' => ''),
		'πΈ' => array('img' => 'crown-queen.png', 'text' => ''),
		'π¦' => array('img' => 'fox.png', 'text' => ''),
		'πΈ' => array('img' => 'frog.png', 'text' => ''),
		'π¦' => array('img' => 'lion.png', 'text' => ''),
		'π§' => array('img' => 'penguin.png', 'text' => ''),
		'π¦' => array('img' => 'owl.png', 'text' => ''),
		'π' => array('img' => 'octopus.png', 'text' => ''),
		'π§Έ' => array('img' => 'teddy-bear.png', 'text' => ''),
		'π’' => array('img' => 'turtle.png', 'text' => ''),
		'π' => array('img' => 'battery.png', 'text' => ''),
		'π' => array('img' => 'call.png', 'text' => ''),
		'β‘' => array('img' => 'flash.png', 'text' => ''),
		'π±' => array('img' => 'responsive-design.png', 'text' => ''),
		'π₯οΈ' => array('img' => 'monitor.png', 'text' => ''),		
		'βοΈ' => array('img' => 'telephone.png', 'text' => ''),
		'π' => array('img' => 'trophy.png', 'text' => '')
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