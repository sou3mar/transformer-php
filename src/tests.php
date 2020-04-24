<?php
    require_once 'Transformer.php'; // Import the library

    $str = 'Language: Persian / Persian | Persian or Persian'; // String to be passed through Transformer class

    // Instance of Transformer Class
    $t = new Transformer($str);

    //  Transformer::replaceHistory(string:$from, string:$to)
    //  replaceHistory method works like a temporary memory & could be used many times
    //  Methods which start with "replace" will use this memory to operate.
    $t->replaceHistory('persian', 'english');
    
    print_r( $t->getReplaceHistory() ); // returns Transformer::replaceHistory as array

    // Transformer::replaceIndex(int:$index)
    $t->replaceIndex(1);
    /*
    /   Replaces by index number which is the occurrence place (starting from 1)
    /   Be aware that replacing the first index will cause the next occurrence to get index 1 after replacement!
    >   Expected Output: "Language: english / Persian | Persian or Persian"
    */

    // Transformer::replaceFirst(int:$count, bool:$isLatin)
    $t->replaceFirst(2, true);
    /*
    /   Changes first n occurrences of Transformer::getReplaceHistory()->from
    /   $isLatin variable gives you the option to use different regex patterns based on your usage.
    /   Second Parameter: Use true for whole-word match, suitable with English/Latin based languages and false for any match-cases, suitable with Persian/Arabic strings
    >   Expected Output: "Language: english / english | Persian or Persian"
    */

    // Transformer::replaceLast(int:$count, bool:$isLatin)
    $t->replaceLast(2);
    /*
    /   Changes last n occurrences of Transformer::getReplaceHistory()->from
    >   Expected Output: "Language: Persian / Persian | english or english"
    */

    // Transformer::replaceRand(int:$count, bool:$isLatin)
    $t->replaceRand(2);
    /*
    /   This method will change n occurrences randomly.
    /   If int:$count is either the same or bigger number than occurrence count, then all of the occurrences will be replaced.
    >   Expected Output: "Language: Persian / english | Persian or english" (Results might be different)
    */

    // Transformer::replaceBefore(int:$index, bool:$isLatin)
    $t->replaceBefore(2);
    /*
    /   Replaces any occurrences before given index.
    >   Expected Output: "Language: english / Persian | Persian or Persian"
    */

    // Transformer::replaceAfter(int:$index, bool:$isLatin)
    $t->replaceAfter(3);
    /*
    /   Replaces any occurrences after given index.
    >   Expected Output: "Language: Persian / Persian | Persian or english"
    */

    // Transformer::highlightWord(string:$word)
    $t->highlightWord("Persian");
    /*
    /   This method will show the start position (from) and the end position (till) of the given word.
    /   The first occurrence will be considered.
    >   Expected Output: array(2) { ["from"] => int(10), ["till"] => int(17) }
    */

    // Transformer::removeBreaks()
    $t->removeBreaks();
    //  Removes all break-line charracters from the string, since they might cause problems in match cases on multi-line strings.

    echo $t->getString(); // Returns the Transformer::str as String
    //  Changed method name from "getAsString()" to "getString()"



    // > Examples:
    // #1 Consider you want to replace/delete the first and last occurrences of a word.
    $str = 'It\'s a simple string containing foo as first foo occurrence and another foo to be the last foo!';
    $t = new Transformer($str);
    $t->replaceHistory('foo', 'bar')->replaceFirst()->replaceLast();
    echo $t->getString(); // Expected Output: "It's a simple string containing Bar as first foo occurrence and another foo to be the last Bar!"
    
    // #2 Consider you want to replace/delete a certain word after it appears once.
    $t = new Transformer('Hello, my name is apple! I like apples. Apples make me happy. Even if they are so expensive!');
    echo $t->replaceHistory('apple', 'orange')->replaceAfter(1, false)->getString();
    // See description of replaceFirst method for more information toward second parameter.
    // Expected Output: "Hello, my name is apple! I like oranges. oranges makes me happy. Even if they are so expensive!"

    // #3 Consider you want to replace/delete a number of occurrences randomly!
    $t = new Transformer('You seem so sick. That dog is sick. I listened to a music that was sick!');
    $t->replaceHistory('sick', 'cool')->replaceRand(2);
    // replaceRand returns an array of randomly selected indexes. For example in this case the result of replaceRand is:
    /*
        Array(
            [0] => 2
            [1] => 1
        )
    */
    echo $t->getString(); // Expected Output: You seem so sick. That dog is cool. I listened to a music that was cool! (Result can be different as it replaces random indexes!)

?>