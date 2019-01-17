<?php
    $str = 'Language: Persian / Persian | Persian or Persian'; // String to be passed through Transformer class

    // Instance of Transformer Class
    $t = new Transformer($str);

    // Transformer::replaceHistory(string:$from, string:$to)
    // replaceHistory method works like a temporary memory & could be used many times
    $t->replaceHistory('persian', 'english');
    /*
    / Methods which use this memory:
    / Transformer::replaceIndex()
    / Transformer::replaceFirst()
    / Transformer::replaceRand()
    */
    print_r( $t->getReplaceHistory() );

    // Transformer::replaceIndex(int:$index)
    $t->replaceIndex(1);
    /*
    / Replaces by index number which is the occurrence place (starting from 1)
    / Be aware that replacing the first index will cause the next occurrence to get index '1' after replacement
    > Output: "Language: english / Persian | Persian or Persian"
    */

    // Transformer::replaceFirst(int:$index, bool:$isLatin)
    $t->replaceFirst(2, true);
    /*
    / Changes first n occurrence of Transformer::getReplaceHistory()->from
    / $isLatin variable gives you the option to use different regex patterns based on your language.
    / Use true with English/Latin and false for Persian/Arabic
    > Output: "Language: english / english | Persian or Persian"
    */

    // Transformer::replaceRand(int:$count) (beta)
    $t->replaceRand(2);
    /*
    / This method will change n occurrences randomly.
    / If int:$count is either the same or bigger number than occurrence count, then all of the occurrences will be replaced.
    > Output: "Language: Persian / english | Persian or english" (Results might be different)
    */

    // Transformer::highlightWord(string:$word)
    $t->highlightWord("Persian");
    /*
    / This method will show the start position (from) and the end position (till) of the given word.
    / The first occurrence will be considered.
    > Output: array(2) { ["from"] => int(10), ["till"] => int(17) }
    */

    echo $t->getAsString(); // Returns the Transformer::str as String
?>