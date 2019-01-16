<?php
    $str = 'Language: Persian / Persian | Persian or Persian';

    $t = new Transformer($str);
    // Transformer::replaceHistory(string:$from, string:$to)
    $t->replaceHistory('persian', 'english');
    print_r( $t->getReplaceHistory() );

    // Transformer::replaceIndex(int:$index)
    $t->replaceIndex(1); // Replaces by index number which is the occuration place (starting from 1)
    
    // Transformer::replaceFirst(int:$index, bool:$isLatin)
    $t->replaceFirst(2, true);
    // Changes first n occurations of Transformer::getReplaceHistory()->from
    // $isLatin variable gives you the option to use different regex pattern according to your language.
    // Use true with English/Latin and false for Persian/Arabic

    echo $t->getAsString(); // Returns the Transformer::str as String
    // Output: Language: english / english | Persian or Persian
?>