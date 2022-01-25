<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class GameEngine
{

	private function hexPair( $decValue )	{
		$hexPair = dechex( $decValue ) ;
		if ( strlen( $hexPair ) === 1 ) $hexPair = '0'.$hexPair ;
		return $hexPair ;
											}

	private function packLevel( $levelArr )	{
		$xSize = $levelArr[0][1] ;
		$ySize = $levelArr[0][2] ;
		$xCoord = $levelArr[0][3] ;
		$yCoord = $levelArr[0][4] ;
		$packString = GameEngine::hexPair(1).GameEngine::hexPair($xSize).GameEngine::hexPair($ySize).GameEngine::hexPair($xCoord).GameEngine::hexPair($yCoord) ;
		$levelStrings = '' ;
		for ( $y=1; $y<=$ySize; $y++ )	{
			for ( $x=1; $x<=$xSize; $x++ )	{
				$element = $levelArr[$y][$x] ;
				switch( $element )	{
					case 1 :	$levelStrings .= 'X' ;
								break ;
					case 2 :	$levelStrings .= '*' ;
								break ;
					case 3 :	$levelStrings .= '.' ;
								break ;
					case 4 :	$levelStrings .= '&' ;
								break ;
					default:	$levelStrings .= ' ' ;
									} ;
											} ;
										} ;
		$lengthOfString = mb_strlen( $levelStrings ) - 1 ;
		$count = 0 ;
		$elementCounter = 1 ;
		$firstFlag = 1 ;
		$prevElement = -1 ;
		while ( $count <= $lengthOfString )	{
			$element = $levelStrings[ $count ] ;
			switch( $element )	{
				case 'X' :	$currentElement = 1 ;
							break ;
				case '*' :	$currentElement = 2 ;
							break ;
				case '.' :	$currentElement = 3 ;
							break ;
				case '&' :	$currentElement = 4 ;
							break ;
				default:	$currentElement = 0 ;
								} ;
			if ( $currentElement === $prevElement )	{
				if ( $elementCounter < 31 )	{
					$elementCounter++ ;
											}
				else	{
					$valString = $prevElement + ( $elementCounter << 3 ) ;
					$packString .= GameEngine::hexPair( $valString ) ;
					$elementCounter = 1 ;
						} ;
													}
			else	{
				if ( $firstFlag === 1 )	{
					$firstFlag = 0 ;
					$prevElement = $currentElement ;
										}
				else	{
					$valString = $prevElement + ( $elementCounter << 3 ) ;
					$packString .= GameEngine::hexPair( $valString ) ;
					$elementCounter = 1 ;
					$prevElement = $currentElement ;
						} ;
					} ;
			$count++ ;
											} ;
		$valString = $prevElement + ( $elementCounter << 3 ) ;
		$packString .= GameEngine::hexPair( $valString ) ;
		$packString .= GameEngine::hexPair( 3 ) ;
		return $packString ;
											}

	private function unpackLevel( $levelString )	{
		$twiceLength = mb_strlen( $levelString ) ;
		$length = intval( floor( $twiceLength / 2 ) ) ;
		if ( $twiceLength !== ( $length * 2 ) ) return False ;
		$levelArr = [] ;
		$key = hexdec( $levelString[0].$levelString[1] ) ;
		if ( $key !== 1 ) return False ;
		$xSize = hexdec( $levelString[2].$levelString[3] ) ;
		$ySize = hexdec( $levelString[4].$levelString[5] ) ;
		$xCoord = hexdec( $levelString[6].$levelString[7] ) ;
		$yCoord = hexdec( $levelString[8].$levelString[9] ) ;
		$levelSize = $xSize * $ySize * 2 + 10 ;
		$loaderCoord = ( $yCoord - 1 ) * $xSize + $xCoord ;
		$counter = 10 ;
		$levelX = 1 ;
		$levelY = 1 ;
		$levelArr[0][1] = $xSize ;
		$levelArr[0][2] = $ySize ;
		$levelArr[0][3] = $xCoord ;
		$levelArr[0][4] = $yCoord ;
		while ( $levelY <= $ySize )	{
			$inpData = hexdec( $levelString[$counter].$levelString[$counter+1] ) ;
			$element = $inpData & 7 ;
			$quantity = ( $inpData & 248 ) >> 3 ;
			for ( $i=1; $i<=$quantity; $i++ )	{
						$levelArr[$levelY][$levelX] = $element ;
						if ( $levelX === $xSize )	{
							$levelX = 1 ;
							$levelY++ ;
													}
						else	{
							$levelX++ ;
								} ;
												} ;
			$counter += 2 ;
									} ;
		if ( hexdec( $levelString[$counter].$levelString[$counter+1] ) === 3 )	{
			return $levelArr ;
																				}
		else	{
			return False ;
				} ;
													}

	private function gameLevel( $level )	{
		switch( $level )	{
			case 1 :	return '01160b0d0920298809180988090a10097819100a196809100a100a080958190809081908092839180908190839101311080a100a68133108210809082110130920093019103120415003' ;
						break ;
			case 2 :	return '010e0a08056110091310092821131009080a100a10111310090a21101113301110111310090809100a084108110a080a08091009080a100a080a080a0809100920092809106103' ;
						break ;
			case 3 :	return '01110a0f024041480930094809080a090a08114809080a100a0950110a080a08091049080a08090821231011080a100a10191b200a100a18112310914803' ;
						break ;
			case 4 :	return '01160d090b70417009102309186110230918092009100a080a1823091809081a090a100a08091023091809100a280a08091023091809081208090a080a080a61100a0809280938091809'.
								'08493809200a1011600908120912100960091809181160496803' ;
						break ;
			case 5 :	return '01110d0f08402960091829400908090a1110094009280a085108191811231011080a100a2123200a0812081108092310110a100a10090849100a10114809080a080a1009481908110809'.
								'5809200958310803' ;
						break ;
			case 6 :	return '010c0b0a02311019080913100908110819131019181113281208111310090809080a081113190809080a0829080a08090a10091809100a09080a08091809080a100a1009180910111809'.
								'184903' ;
						break ;
			case 7 :	return '010d0c06033829103918210809101108120811200a3011100a1019182108290a21080a10190813090809080a080a080a081b09080920191b0908090812080908091b0908091019082908'.
								'214803' ;
						break ;
			case 8 :	return '01101102071021600910591009200a180a080a08091009080a09080a0809100a10091009100a080a10092021080a0908091021081108090a080a080a10111811200a08090a0918090819'.
								'100a200a080a080a08090821104910191019400930094009300940093309400933094009330940413003' ;
						break ;
			case 9 :	return '011112020b50395009101b093029101b093009301b0930091011101b0930110811101b09281908412809081a08112829100a080a083918090a080a18091811100a100a200a100a083908'.
								'12080a08292809080a20094821081960091009680910096809100968212803' ;
						break ;
			case 10 :	return '011514030670216031100960093809600910210819101910290819200908110821181a08092009080908121812080a18092319101a09200a10092b11080a18090812081208092b211809'.
								'100a20092b0910091809080a080a080a08092b091009083908192b0910091809100a080a10092b09101908090812080a080a3920090809100a300940090809081a081a08094009080938'.
								'0908094009084908094009580940692003' ;
						break ;
			case 11 :	return '01130f08045021502108091009401910190a08093811300a10093011100a0812110811300910090a11280930090809080a0812080908192809180a08091009080a084920091012080918'.
								'290811080a48110b2019104913081309082138091b090b0960092b0960396003' ;
						break ;
			case 12 :	return '010d10070e104920090c0b0c090c0b0c0920090b0c0b0c0b0c0b0920090c0b0c0b0c0b0c0920090b0c0b0c0b0c0b0920090c0b0c0b0c0b0c092019181930091809203108395811080a08'.
								'0a080a080a080a0819080a080a080a080a081108090a080a080a080a080a091009180a080a1809100910291009102118210803' ;
						break ;
			case 13 :	return '01140d080520494819181110291819300910091829101208090a08091009101b08110809100a09080a11080908090b090b0811101108090a1009201b0811080a09200a0809080908090b'.
								'090b0811201110110a080a081b0811080a0811180910090a090b090b08190812100a180a100a1b080908090a10312011100908091809205108297003' ;
						break ;
			case 14 :	return '01110d08058108097009080908090831280908090809100a080a080a080a09100908090809180a080a18110819080908090a080a080a191b110809180a080a10111b1108191a080a0811'.
								'1b112809081108111b31181108111b09202928194009280950391003' ;
						break ;
			case 15 :	return '01111107073821502110094811100910094809100a080a0809381908090a18212009100a10110a180920091009180a0809080a0920091009300a0821081108210a1128090809080a092b'.
								'09080918090809100a1b0c0b080a09082910092b09180910091819083910090812100910093809100928093831180960293803' ;
						break ;
			case 16 :	return '010e0f040629480918114009200910211009080a1021100910091012080a180a09101910090a201110091011100a080a08110809080a10110811080b09080910090a110a10090b090819'.
								'180a13110b09100920090b0c1b091009081208092b09100910491009100950214003' ;
						break ;
			case 17 :	return '0112100b0338392839280928092809080a080a08092809120809184908090819331118090809180a331108090809080908193328191821081908090a1910090a1809100a100908090809'.
								'100a081a1009080a1108090809180a080a081912080908090829280a1809080928190819180908093809280918093841100970210803' ;
						break ;
			case 18 :	return '01160d100330615009100b10111809500908090b3809283108111b090821181110111b212829080a08111b200a0809100a10112813081108090811081110290a190a09080a1009180908'.
								'110819100920110a0812080908091009181208090809080a0809080a110809100990091089100990210803' ;
						break ;
			case 19 :	return '011c140d024031b00920218829080a18098809181120217009080a111011200970091809102908097009080912080a200908097009100a080a08190809080970090809180a1009080908'.
								'097009080910090a0918090809681108211809080908096809100a10290809080908213811200a280a101910491019080a080a09080a0809182b1128113009101110092b11082220310a'.
								'1118090b110b19201170092309081110791823091009100968291011102188210803' ;
						break ;
			case 20 :	return '011414070538614009530930190b090b090b090b0913093009184b093009100a080a080a080c0b0c0b092839083910211809201110091011200a08092009080a0811080910090a090819'.
								'08190a1819080a100a080a1809080a080a080a08111009080a081138090a0811180a210a210a111029101118092009100918090a0811180908090812100918091809080a0809100a2009'.
								'1819080908120809100a0819280908092009080a08113809084108094009500940611803' ;
						break ;
			case 21 :	return '01100e030b1851300913100918093009133009300913100910211839100910111009600910091009101110091029081110210819100a1029080910110809080a100a1009080a1011100a'.
								'100a18091831081108392009200950313803' ;
						break ;
			case 22 :	return '0116140c05602138611029180920091009100a100918111009080a080a080a100a0809080a080a180910110a080a18091009080a180a08090819186108110809100a080a091009330908'.
								'0a091009080918091009331108091009101108110809082b09100910090809300a33080a080910090809080a08110809330910091009100a080a0910093309080a091009080a18091011'.
								'0a2910091009080a080a0821080a080a100a080a0910110809280a080a080a080a181908091031080a200a20090809480908390809083908090a50093809185938295003' ;
						break ;
			case 23 :	return '01190e060838399009100910217809080a090a080910113841100910091849231009080a090a0809100a09100918112309080928090a1009301113090b200a091009080a20090a10111b'.
								'101110090a08090a100910091811230811080a09280a81100912090a10097009080a091009100a0970091009100918097021102988215803' ;
						break ;
			case 24 :	return '011513061018515809432140090b090b09230910094009431208094009280b1910211849100a080918091809280a180a080a100a0809180910092009100a080a09100918110829180910'.
								'0910091809080a2809182108091011100a091809081110091009100920110a19200910110809080a200a080910091009180908292009081108090811081120090a090809100a100a080a'.
								'180920090809100a091a100918092019100a3029301110091009100958512003' ;
						break ;
			case 25 :	return '011711120a7821703110292039380918092009300a080a081108090809080920091021080a1009280b092009300a0809080908110b090b0920110a210a080a080a08110b090b09200928'.
								'0920210b192009080a183110090b090b391a1138090b090b11300920090a090a190b080b11082108092a2009081b110809200a28091809081b1108091811081128191b1108310a311039'.
								'400920091009285120212803' ;
						break ;
			case 26 :	return '010f0f0505493009380930093821181108210809100918110809081120091809081a080a101209180910090811080a1009180910090811100a0841101a080a0910090809181118230908'.
								'0908091809080913080b0908091809080908111b090829080a10091b092811182930292003' ;
						break ;
			case 27 :	return '01170d0b0c088930091b18092009181918112b100a1108090809080a080918093309100a1009100a100918093309100910090809080908111049080a100a080908091019100928090a11'.
								'0a0811081118090811180a2009080a100a18090809080910110819080910290a0908090809080a0812280a180a28090809080a200a110a0841080908392011301938315003' ;
						break ;
			case 28 :	return '010f11070228394009100910094009080a18093819081108092021080a100908111809380910111009080a080a21080a08091009081208091009100a0910090a100a18090a1009081110'.
								'120918120819081210091009100a08112821080a101110090a1113111821080b0923291009083b1120092318130920591003' ;
						break ;
			case 29 :	return '01180b140a802950310819182118292019080a080a100a0829101108090a080a200a08091811231812080a080a100a18090a191308090811080918190a11080910112320090819200920'.
								'112320090811100a10190a08111331100a10091021083120091819380948790803' ;
						break ;
			case 30 :	return '010e1409070829480918391809080a081918091809080a201208091811082118091019080910090819100918091009081118090812200a0809180918090809080a084908091809100908'.
								'09180a2118090809100a280a1009081118290811085110192309080a100a08112b09081209101113081309080a100a08112b0a18091019105108214803' ;
						break ;
			case 31 :	return '010f0c0e0a08394009100910291811100910091b190809100a0910091b10090809080a080912081b10090809100a0910091b080b0908091809080a510a380a080a081910091012080918'.
								'09083110111208093009301130410803' ;
						break ;
			case 32 :	return '011210090310217009104928111011100918092809100a09080a080a182110090a100a1009080a080a091021100a1108090a080a2811100910090809181a1011080a200a100a11082908'.
								'0a080a08090a09100910091811101910190a080920091009232809202133213009232148091b1160091b0968295003' ;
						break ;
			case 33 :	return '010d0f02053021282910092011280a091811080a101108190809080a080a0809080a100908210811180a09100923090a080a080910092309180a091009231012081108091b0809080a18'.
								'0908310a080a10093009181930090a08193809100948211803' ;
						break ;
			case 34 :	return '010c0f0b0b7128111019180a180a0829081108120811180a08092011081a0809082918090809080a081910091009100a0811080a09080a092011181309084113080a080908112b09080a'.
								'0908192309100a08211311206903' ;
						break ;
			case 35 :	return '0114100b026110391809200908212311181209382b111809081918110823190811081910091823090809080a080a2809081108210809100a080a11100938290809102108110811081110'.
								'0908090a181108112011080a100a1009081108410809080a080a200908093009100a08110811080908093009081228121009301108110819080a10093809200908092009383108313003' ;
						break ;
			case 36 :	return '01121308092821601910114021100a10094009180a080a10212809080a1809080a1809082910091009180a0809080913190a090a08210a211309080918290811081b0908090a09081108'.
								'11081110130908090809200a281b090809182108191013090819081108091011081b0910110a08210a08191309100918112009080913090811081211100a080908210809282208093009'.
								'080a0819200930091809083130296003' ;
						break ;
			case 37 :	return '01150f0a0e59500933184910093318091011180910091319080a200a280910091b080a080a08091019180910091b090a29200910091019200918090a1009080a1910091012080a080a10'.
								'0a11100a08091009100a18090a0910112009101908110809100a08391809100a080a081108114809200a100a100948111809080918095029082970195003' ;
						break ;
			case 38 :	return '010e0f0b040849280923181120090b090b09100a0811101123090809101108090823091009101928090a08110a08190819100a200908090a100a080a080a09100908090809100a080a08'.
								'110809080910191011100908092011081108110809100a0809100a100910190a080a18192009102930213803' ;
						break ;
			case 39 :	return '0117120c06701998110b1988092309286923092011181128112331101211100a10112320113012080a091023091811100a081108120809080923091019100a0811080a10090811081910'.
								'0908110829081948090811180a100a0829081910090809080a1910090829080908210809180a180938093009100a08090a080a080a1910093009081a09080a1809082130092009101208'.
								'095831181980296803' ;
						break ;
			case 40 :	return '010b0b09023021083910090809280a10090809180a11080a0908110a091b0908091009080a1b1009100908090b080b0908110809180908090a080908090a100a20090809103908213003' ;
						break ;
			case 41 :	return '01140f12095829701118116011280958111012100950110812100a08095009080a200a080918211809181208290809104108112009080913581a080908090b0908390811181108090b09'.
								'08390b08090a080a215b0809180a0879100a10096811101970211003' ;
						break ;
			case 42 :	return '010d12030208412809081118211009080a180a18091009100a080a081a0910090812090809180908110a200a18090809100a102a19080a2108091811100a230918110811230912081108'.
								'1123181918230910090811080923091209100908092309100910094809102108110a192809200938311803' ;
						break ;
			case 43 :	return '011110100420612809501120091009080912080a100920090a08090a0910111009181108110809080a080908111809180a08090a1009080920091809080a180908092011080a080a1811'.
								'0809200910091011100a08092009201108120908090831121809180908092309104108090b091b08114009231809400923180940494003' ;
						break ;
			case 44 :	return '0119130e0830318029180980091809080908296009080a0809100a203130110a10190811380920191012080a080a080910111831380a1831081118111041080920090809100908190819'.
								'302108090a09080910090809081908210811130809180a08110809100a100a10090a111308090a11101108091009080908092813110811080a080908211809081108091309200a100920'.
								'29200913090809080910114031130918090811700913291009700913380970111019101178491003' ;
						break ;
			case 45 :	return '01130b0a08403940291009102128091809180a20091021080912081108111009081130090809101108211019080a090a100a100a10111b20090811100918111b093009081908191b0910'.
								'19100a100a1049081118091809504903' ;
						break ;
			case 46 :	return '0116110c0f2049102138091811102110093809180a1809100a1009380910090811080928212011080a180a08120908091809202110091009080a080a1831102120191b1118090a080910'.
								'0908212b1130091009080908112b39080910090a18191b09180918110809080a0918091b091011380a100a0908290811081a111009080a1809280918091009081910192809180a10090a'.
								'102138291009180970414803' ;
						break ;
			case 47 :	return '01130f0a040829700918097009080908314809300a08312009080a08110a0819180920090821080a200a080920090829080910090a0831102108110a3011100a09100a10090811081108'.
								'11480908091b0908391019101b10092821080908091b090809500908190809080950093809504903' ;
						break ;
			case 48 :	return '01100f080c3821600910115809181150090812081138190a100a08111821200a18090819100908291009080920090809230a080908090809180a08230908090809100a080908090b0c13'.
								'09080908191021081908091821100a10110a112819080a2809380910111809384903' ;
						break ;
			case 49 :	return '01131003083061301113200918092811130c080a200a08092011130c0b09080908090a08112009130c0b0908090809080a100908211b0910092009080908091011080950090809100a08'.
								'0a08191009080908110809080a180a180908091809101912180908090809080908092009180a1809080908291009080a090829300910090a180918091809100910091019181128091009'.
								'100930092011102130310803' ;
						break ;
			case 50 :	return '011510060a28694009201920094009280a080a102120210809180a080a20091811080a10090a21080a080a08090819180908091819100a08090809080a100a1009100a10090821080908'.
								'110a2108090a09100a102108111019080908090809100a1011280a180a1809080a08090831100910111009080a09100910091b08290a10091009080910093b09081208090a0809080910'.
								'093b09480910093b391011104928210803' ;
						break ;
			case 51 :	return '01100e060a29082130091b090809102118091b19100a100918092311080a100a1908112311180a100908191b0811080a080a0809080908112009100a100908091011080908190829080a'.
								'080908090a100a2011100a180a200a10111809080a0812080a08211031101910090811202120196803' ;
						break ;
			case 52 :	return '01150e060d0821801110296009380908293009080a191019180930091309100a0908091009080930091309301209081920090b0c09080910090a080a20311309101128110a0918110b0c'.
								'0a100a08090811100a28111311100a180918390b0c110a111829280913100a082948091009180968416803' ;
						break ;
			case 53 :	return '010d13050818511809101918091809080a180a1009180910210a111811080910091009101110090b0c18091009101113091009100918090b0c090811100908090a0913090a0809100908'.
								'0a080913091009100908090809140910091009080a080913090a111009200b0c09100908191009100910192021101110390a19080a300a101110111809187103' ;
						break ;
			case 54 :	return '011714050c08a91009181110091809180918091009080a280a180a180a183908091009181908110a21180908110a3118091811080a18090833091809080a081908091009083329181908'.
								'4913091809082150091309080a18091009081108190819131108091021080918091811131108191011500a130938110809180918111009181110310871081950091809200a0811080a10'.
								'09080a080a080a18090809201108090a11080a091011081120090811100a08120821080a100a0809080908115009180930c103' ;
						break ;
			case 55 :	return '01160f060908b99811200a08093011080918111031081910090a1108210a0918110a09231809080908091009200a08092311080908090809080a0809080908090809231118090809080a08'.
								'0912180923110a09080908090809080a080a110a09231118090809181a1809230920090809100a0918090831080a1908111009081912100a180a080910112809080a100a081118091829'.
								'1809183938494003' ;
						break ;
			case 56 :	return '010e100c08512009402108090831080910190809080a080a080a100a081138090a18210a1012091019100910110809080a1118110a09180a10092009100a080a081920090809180a1009'.
								'20090811180908091811102908091809480918093b1918093b0928491803' ;
						break ;
			case 57 :	return '01120b08064821304910112011100a300a08311811081118111b11080912080a0812090a111b1108093809181b11100a09081912181b11080a1012100a081123210a3839100910394021'.
								'6003' ;
						break ;
			case 58 :	return '011b14160f70318829200988091011080910296809180c0b091309180920290821080a090b091b200920091819101108090c231108112009080a30110809130913110809203108091809'.
								'08090c0b29080920091809080a090a0908090809132908092009080a100a280908090c0b2009080920110811100a081908091011100908092809100a100a081908290811080928190a19'.
								'0a19102108110809202108094819100908092009100a0809100a21101912090831300a08090809102110090a0918290809100a09080970091809100a1009081110111041181110191041'.
								'58219803' ;
						break ;
			case 59 :	return '011d140e0e4821c8091009c809104160391009300960091809080908090809080918115809080a280a1011100a08095019080a0908091009080928491009100a1009100a090809081208'.
								'091809080910090811080918092819200a080908091009080910090a1809081910091009081209080910090809200a11080a10091811080a1009080908310a080a080920111009180a20'.
								'1311100920190809080a080a081910190b0c1128111012200a2811231110111011180a10090a091011230c0b190809100a10090809080a111011230c0b290811100a1009080a08091009'.
								'230c0b191009200a0821180908230c0b192009180910091009100910130c0b19304110594003' ;
						break ;
			case 60 :	return '011a1007094029a80918219009080a2021102148091809080a091021100908590809180a180918090809132809080a10210809100910090809130a1009180a1009100a0809080a080b11'.
								'08090b0c090809080a080a0811101120090b09100913090a18091811201208090b0910091309080a080a100a080a08111811080b0910090b0c1208090811180a08090a09080a08090b09'.
								'100913093011180928090b0910091339101908310b1108090812900c0b191091101329803103' ;
						break ;
			default:	return False ;
						break ;
							} ;
											}

	private function languageControl()	{
		$defaultLanguage = App::currentLocale() ;
		$user = auth() -> user() ;
		$sessionId = session() -> getId() ;
		if ( $user !== Null )	{
			$usersTable = 'game-users' ;
			$userId = $user -> id ;
			$userEmail = $user -> email ;
			if ( DB::table( $usersTable ) -> where( 'id', $userId ) -> exists() )	{
				$language = DB::table( $usersTable ) -> where( 'id', $userId ) -> value( 'language' ) ;
				if ( $language == 'ua' ) $language = 'uk' ;
				App::setLocale( $language ) ;
																					}
			else	{
		$defaultLanguage = App::currentLocale() ;
				DB::table( $usersTable ) -> insert( [ 'id' => $userId, 'email' => $userEmail, 'language' => $defaultLanguage, 'level' => 1, 'limit' => 1,
														'moves' => 0, 'control' => 'None', 'state' => GameEngine::gameLevel( 1 ) ] ) ;
				if ( $defaultLanguage == 'ua' ) $defaultLanguage = 'uk' ;
				App::setLocale( $defaultLanguage ) ;
					} ;
								}
		else	{
			$sessionsTable = 'game-sessions' ;
			$sessionId = session() -> getId() ;
			if ( DB::table( $sessionsTable ) -> where( 'id', $sessionId ) -> exists() )	{
				$language = DB::table( $sessionsTable ) -> where( 'id', $sessionId ) -> value( 'language' ) ;
				if ( $language == 'ua' ) $language = 'uk' ;
				App::setLocale( $language ) ;
																						}
			else	{
				DB::table( $sessionsTable ) -> insert( [ 'id' => $sessionId, 'language' => $defaultLanguage, 'level' => 1, 'limit' => 1,
														'moves' => 0, 'control' => 'None', 'state' => GameEngine::gameLevel( 1 ) ] ) ;
					} ;
				} ;
										}

	private function gameMove( $gameVars )	{
		$moves = $gameVars[1] ;
		$control = $gameVars[2] ;
		if ( $control === 'Up' Or $control === 'Down' Or $control === 'Left' Or $control === 'Right' )	{
			switch( $control )	{
				case 'Up' :		$xShift = 0 ;
								$yShift = -1 ;
								break ;
				case 'Down' :	$xShift = 0 ;
								$yShift = 1 ;
								break ;
				case 'Left' :	$xShift = -1 ;
								$yShift = 0 ;
								break ;
				case 'Right' :	$xShift = 1 ;
								$yShift = 0 ;
								break ;
								} ;
			$levelArr = GameEngine::unpackLevel( $gameVars[3] ) ;
			$xCoord = $levelArr[0][3] ;
			$yCoord = $levelArr[0][4] ;
			$xSize = $levelArr[0][1] ;
			$ySize = $levelArr[0][2] ;
			$levelAbstract = [] ;
			for ( $y=0; $y<=($ySize+1); $y++ )	{
				$levelAbstract[$y][0] = 1 ;
				$levelAbstract[$y][$xSize+1] = 1 ;
												} ;
			for ( $x=1; $x<=$xSize; $x++ )	{
				$levelAbstract[0][$x] = 1 ;
				$levelAbstract[$ySize+1][$x] = 1 ;
											} ;
			for ( $y=1; $y<=$ySize; $y++ )	{
				for ( $x=1; $x<=$xSize; $x++ )	{
					$element = $levelArr[$y][$x] ;
					switch( $element )	{
						case 1 :	$levelAbstract[$y][$x] = 1 ;
									break ;
						case 2 :	$levelAbstract[$y][$x] = 2 ;
									break ;
						case 3 :	$levelAbstract[$y][$x] = 0 ;
									break ;
						case 4 :	$levelAbstract[$y][$x] = 2 ;
									break ;
						case 0 :	$levelAbstract[$y][$x] = 0 ;
									break ;
										} ;
												} ;
											} ;
			$element = $levelAbstract[$yCoord+$yShift][$xCoord+$xShift] ;
			$element2 = $levelAbstract[$yCoord+$yShift+$yShift][$xCoord+$xShift+$xShift] ;
			if ( $element === 0 )	{
				$moves++ ;
				$gameVars[1] = $moves ;
				$control = 'None' ;
				$gameVars[2] = $control ;
				$xCoord += $xShift ;
				$yCoord += $yShift ;
				$levelArr[0][3] = $xCoord ;
				$levelArr[0][4] = $yCoord ;
				$gameVars[3] = GameEngine::packLevel( $levelArr ) ;
				return $gameVars ;
									}
			elseif ( $element === 2 And $element2 === 0 )	{
				$moves++ ;
				$gameVars[1] = $moves ;
				$control = 'None' ;
				$gameVars[2] = $control ;
				if ( $levelArr[$yCoord+$yShift][$xCoord+$xShift] === 2 )	{
					$levelArr[$yCoord+$yShift][$xCoord+$xShift] = 0 ;
																			}
				else	{
					$levelArr[$yCoord+$yShift][$xCoord+$xShift] = 3 ;
						} ;
				if ( $levelArr[$yCoord+$yShift+$yShift][$xCoord+$xShift+$xShift] === 0 )	{
					$levelArr[$yCoord+$yShift+$yShift][$xCoord+$xShift+$xShift] = 2 ;
																							}
				else	{
					$levelArr[$yCoord+$yShift+$yShift][$xCoord+$xShift+$xShift] = 4 ;
						} ;
				$xCoord += $xShift ;
				$yCoord += $yShift ;
				$levelArr[0][3] = $xCoord ;
				$levelArr[0][4] = $yCoord ;
				$gameVars[3] = GameEngine::packLevel( $levelArr ) ;
				return $gameVars ;
															}
			else	{
				$control = 'None' ;
				$gameVars[2] = $control ;
				return $gameVars ;
					} ;
																										} ;
		return $gameVars ;
											}

	private function mainLogic( $id, $dbTable )	{
		$gameVars = [] ;
		$gameVars[0] = DB::table( $dbTable ) -> where( 'id', $id ) -> value( 'level' ) ;
		$gameVars[1] = DB::table( $dbTable ) -> where( 'id', $id ) -> value( 'moves' ) ;
		$gameVars[2] = DB::table( $dbTable ) -> where( 'id', $id ) -> value( 'control' ) ;
		$gameVars[3] = DB::table( $dbTable ) -> where( 'id', $id ) -> value( 'state' ) ;
		$gameVars[4] = DB::table( $dbTable ) -> where( 'id', $id ) -> value( 'limit' ) ;
		if ( $gameVars[2] !== 'None' )	{
			if ( $gameVars[2] === 'Up' Or $gameVars[2] === 'Down' Or $gameVars[2] === 'Left' Or $gameVars[2] === 'Right' )	{
				$gameVars = GameEngine::gameMove( $gameVars ) ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'level' => $gameVars[0], 'moves' => $gameVars[1], 'control' => $gameVars[2],
																			'state' => $gameVars[3] ] ) ;
																															}
			elseif ( $gameVars[2] === 'Reload' )	{
				$gameVars[2] = 'ReloadQ' ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2] ] ) ;
													}
			elseif ( $gameVars[2] === 'RldQNo' )	{
				$gameVars[2] = 'None' ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2] ] ) ;
													}
			elseif ( $gameVars[2] === 'RldQYes' )	{
				$gameVars[2] = 'None' ;
				$gameVars[1] = 0 ;
				$gameVars[3] = GameEngine::gameLevel( $gameVars[0] ) ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2], 'moves' => $gameVars[1],
																			'state' => $gameVars[3] ] ) ;
													}
			elseif ( $gameVars[2] === 'Prev' )	{
				$gameVars[2] = 'PrevQ' ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2] ] ) ;
												}
			elseif ( $gameVars[2] === 'PrvQNo' )	{
				$gameVars[2] = 'None' ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2] ] ) ;
													}
			elseif ( $gameVars[2] === 'PrvQYes' )	{
				$gameVars[2] = 'None' ;
				$gameVars[1] = 0 ;
				$gameVars[0]-- ;
				$gameVars[3] = GameEngine::gameLevel( $gameVars[0] ) ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2], 'moves' => $gameVars[1], 'level' => $gameVars[0],
																			'state' => $gameVars[3] ] ) ;
													}
			elseif ( $gameVars[2] === 'Next' )	{
				$gameVars[2] = 'NextQ' ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2] ] ) ;
												}
			elseif ( $gameVars[2] === 'NxtQNo' )	{
				$gameVars[2] = 'None' ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2] ] ) ;
													}
			elseif ( $gameVars[2] === 'NxtQYes' )	{
				$gameVars[2] = 'None' ;
				$gameVars[1] = 0 ;
				$gameVars[0]++ ;
				$gameVars[3] = GameEngine::gameLevel( $gameVars[0] ) ;
				DB::table( $dbTable ) -> where( 'id', $id ) -> update( [ 'control' => $gameVars[2], 'moves' => $gameVars[1], 'level' => $gameVars[0],
																			'state' => $gameVars[3] ] ) ;
													} ;
										} ;
		return $gameVars ;
												}

	private function gameLogic()	{
		$user = auth() -> user() ;
		$sessionId = session() -> getId() ;
		$gameVars = [] ;
		if ( $user !== Null )	{
			$usersTable = 'game-users' ;
			$userId = $user -> id ;
			$gameVars = GameEngine::mainLogic( $userId, $usersTable ) ;
								}
		else	{
			$sessionsTable = 'game-sessions' ;
			$sessionId = session() -> getId() ;
			$gameVars = GameEngine::mainLogic( $sessionId, $sessionsTable ) ;
				} ;
		return $gameVars ;
									}

	private function winForLevel( $levelArr )	{
		$xSize = $levelArr[0][1] ;
		$ySize = $levelArr[0][2] ;
		$winOfLevel = True ;
		for ( $y=1; $y<=$ySize; $y++ )	{
			for ( $x=1; $x<=$xSize; $x++ )	{
				$element = $levelArr[$y][$x] ;
				switch( $element )	{
					case 2 :	$winOfLevel = False ;
								break 3 ;
									} ;
											} ;
										} ;
		return $winOfLevel ;
												}

	private function setLimitLevel( $limitLevel )	{
		$user = auth() -> user() ;
		$sessionId = session() -> getId() ;
		if ( $user !== Null )	{
			$usersTable = 'game-users' ;
			$userId = $user -> id ;
			DB::table( $usersTable ) -> where( 'id', $userId ) -> update( [ 'limit' => $limitLevel ] ) ;
								}
		else	{
			$sessionsTable = 'game-sessions' ;
			$sessionId = session() -> getId() ;
			DB::table( $sessionsTable ) -> where( 'id', $sessionId ) -> update( [ 'limit' => $limitLevel ] ) ;
				} ;
		return ;
													}

    public function handle(Request $request, Closure $next)
    {
		$numberOfLevels = 60 ;
		GameEngine::languageControl() ;
		$gameVars = GameEngine::gameLogic() ;
		$currentLevel = $gameVars[0] ;
		$moves = $gameVars[1] ;
		$control = $gameVars[2] ;
		$limit = $gameVars[4] ;
		$previousLevel = True ;
		$nextLevel = True ;
		$winOfGame = 'False' ;
		if ( $currentLevel === 1 ) $previousLevel = False ;
		if ( $currentLevel >= $limit ) $nextLevel = False ;
		$levelArr = GameEngine::unpackLevel( $gameVars[3] ) ;
		$levelArr[0][0] = $currentLevel ;
		$levelArr[0][5] = $moves ;
		$levelArr[0][6] = $control ;
		$levelArr[0][7] = $previousLevel ;
		$levelArr[0][8] = $nextLevel ;
		$winOfLevel = GameEngine::winForLevel( $levelArr ) ;
		if ( $winOfLevel === True )	{
			if ( $limit === $currentLevel And $currentLevel < $numberOfLevels )	{
				$limit++ ;
				GameEngine::setLimitLevel( $limit ) ;
				$gameVars[4] = $limit ;
				$nextLevel = True ;
				$levelArr[0][8] = $nextLevel ;
																				} ;
			if ( $currentLevel === $numberOfLevels )	{
				$winOfGame = 'WinOfGame' ;
														}
			else	{
				$winOfGame = 'WinOfLevel' ;
					} ;
									} ;
		$levelArr[0][9] = $winOfGame ;
		
		$request -> attributes -> add( array( 'levelArr' => $levelArr ) ) ;

        $response = $next($request);
		return $response;
    }
}
