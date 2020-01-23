<?php
/*stemmer.php
by Spyros Saroukos
Based on the work by George Ntais
Realeased under LGPL
*******************
returns an array called returnResults[]
returnResults[0]= the stem
returnResults[1]= the number of executed rules, for debugging testing purposes



****************
the encoding of this file is iso-8859-7 instead of UTF-8 on purpose.
*/


function stemWord($w) {
    $numberOfRulesExamined = 0; //this is the number of rules examined. for deubugging and testing purposes

//it is better to convert the input into iso-8859-7 in case it is in utf-8
// this way we dont have any problems with length counting etc

    $encoding_changed = FALSE;
    if (mb_check_encoding($w, "UTF-8")) {
        $encoding_changed = TRUE;
        $w = mb_convert_encoding($w, "ISO-8859-7", "UTF-8");
    }
    $w_CASE = array(strlen($w));//1 for changed case in that position, 2 especially for �


//first we must find all letters that are not in Upper case and store their position
    $unacceptedLetters = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
    $acceptedLetters = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");

    for ($k = 0; $k <= 32; $k = $k + 1) {
        for ($i = 0; $i <= strlen($w); $i++) {
            if ($w[$i] == $unacceptedLetters[$k]) {
                if ($w[$i] == "�") {
                    $w[$i] = "�";
                    $w_CASE[$i] = 2;
                } else {
                    $w[$i] = $acceptedLetters[$k];
                    $w_CASE[$i] = "1";
                }
            }
        }
    }
//stop-word removal
    $numberOfRulesExamined++;
    $stop_words = '/^(���|���|���|���|���|���|���|��|���|���|��|���|���|�����|�����|�������|���|�������|�������|�������|������|���������|�������|����������|������|���|���|���|���|�����|������|���|��|���|�������|���������|����|����|��������|�����|���|�������|������|��|��������|���|�����|���|���|�����|���|���|������|���|���|���|���|���|��|���|���|���|���|���|���|���|���|�����|���|������|����|��|���|���|���|���|���|���|���|���|���|���|���|���|����������|���|���|���|���|���|���|���|���|��|���|���|���|���||��|���|��|���|���|���|���|���|�����|���|���|���|�������|���|���|���|����|��|��|���|���|���|���|��|�����|������|���|��|���|�������|������|���|�����|���|���|���|���|���|���|���|��|���|���|���|���|��|������|����|�����|�������|�����|���|�����|�������|�����|����|����|������|�����|������|����|�����|��|����|���|���|���|��|�������|�����|����������|���|��|������|���|������|���|���|�����|������|������|���|���|������|��������|���|��|����|���|�����|�����|���|����|���|���|���|����|��|���|����|�����|�������|���|������|���|���|����|�����|�����|�����|�����|������|�����|�����|���|���|���|���|���|���|���|��|�|���|���|���|���|���|���|������|�����|���|����|�����|����|�����|��|��|���|���|�|��|���|���|�����|��|��|���|���|���|���|���|���|���|���|��|���|���|�����|���|���|����|���|����|���|���|���|���|���|����|������|�������|�����|���|���|������|�����|�����|���|����|����|������|�������|����|���|���|���|���|���|��|���|������|���|���|���|�����|���|���|�����|���|���|���|������|��|���|��|���|���|���|���|������|���������|����|��������|���|���|����|�����|������|���|��|���|��|����|������|�������|������|���|���|���|���|��|��������|���|�����|���|�����|��������|������|���|���|����|���|����|������|�����|��|����|���|�����|����|��|���|���|���|���|���|�����|��������|������|��������|���|���|���|������|�������|������|�����|���|��|���|���|��|���|���|���|��|���|���|���|���|��|���|���|���|���|���|���|���|���|���|���|���|�����|����|�������|���|��|�|��|���|���|��|���|���|��|���|���|���|�������|������|�������|����������|����|��|���|���|���|���|���|���|�����������|������������|������������|����������|������������|�����������|������������|������������|������������|�������������|������������|�����������|����|����������|����|���|���|���|���|���|���|���|���������|���|����������|���������|����������|����������|���������|����������|����������|����������|����������|�����������|����������|����|���|���|���������|��|����|���|���|����|���|���|���|���|���|���|���|��|����|���|�������|������|������|���|���|����|���|���|����|����|�������|�����|������|���|��|���|�������|���|���|����|���|���|����|�����|����|���|��|���|���|���|����|���|����|���|�����|�������|������|���|����|���|�����������|���������|��������|����|������|�������|�������|���������|���|���|���|���|��|��|���|���|��|���|���|���|��|���|���|���|���|���|���|��|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|��|����|���|���|���|���|���|������|��|���|����|���|���|���|���|���|���|���|���|���|���|���|���|����|����|����|���|����|����|�����|����|��|���������|���|������|�������|�������|������|�����|��|����|���|���|���|���|����|������|��|���|���|������|�������|���|���|���|��|���|���|���|��|���|���|������|������|���|���|��|���|���|���|���|���|���|���?�|����|�����|����|�����|�����|����|�����|�����|�����|�����|������|����|���|����������|�����������|����|��|���|���|�����|��|���|����|���|���|���|���|���|���|���|���|��|����|���|�����|������|������|���|���|��|���|���|���|���|���|���|���|���|�����|���|��|���|���|���|���|���|��|���|���|���|���|��|���|����|��|���|���|���|���|��|���|���|���|����|�����|�������|���|����|��|���|�|��|���|���|���|���|���|���|��|��|���|���|��|����|���|������|�����|����|������|���|��|���)$/';

    if (preg_match($stop_words, $w)) {
        //return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        // Remove stop words
        return returnStem('', '', $encoding_changed, $numberOfRulesExamined);
        exit;
    }

// step1list is used in Step 1. 41 stems
    $step1list = Array();
    $step1list["�����"] = "��";
    $step1list["������"] = "��";
    $step1list["������"] = "��";
    $step1list["������"] = "���";
    $step1list["�������"] = "���";
    $step1list["�������"] = "���";
    $step1list["�������"] = "���";
    $step1list["������"] = "���";
    $step1list["�������"] = "���";
    $step1list["������"] = "��";
    $step1list["�����"] = "��";
    $step1list["������"] = "��";
    $step1list["�������"] = "����";
    $step1list["��������"] = "����";
    $step1list["��������"] = "����";
    $step1list["�����"] = "���";
    $step1list["�������"] = "���";
    $step1list["������"] = "���";
    $step1list["�������"] = "���";
    $step1list["�����"] = "���";
    $step1list["�������"] = "���";
    $step1list["������"] = "���"; //Added by Spyros . also at $re in step1
    $step1list["������"] = "���";
    $step1list["�������"] = "���";
    $step1list["�����"] = "���";
    $step1list["�������"] = "���";
    $step1list["������"] = "���";
    $step1list["�������"] = "���";
    $step1list["���"] = "��";
    $step1list["�����"] = "��";
    $step1list["����"] = "��";
    $step1list["�����"] = "��";
    $step1list["��������"] = "������";
    $step1list["����������"] = "������";
    $step1list["���������"] = "������";
    $step1list["����������"] = "������";
    $step1list["�������"] = "�����";
    $step1list["���������"] = "�����";
    $step1list["��������"] = "�����";
    $step1list["���������"] = "�����";

    $v = '(�|�|�|�|�|�|�)';    // vowel
    $v2 = '(�|�|�|�|�|�)'; //vowel without Y

    $test1 = true;


//Step S1. 14 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|����|���|�����|�����|����|�����|���|�����|����|������|�����|�����|������)$/';
    $exceptS1 = '/^(������|����|���|������|��|������|����|�������|����)$/';
    $exceptS2 = '/^(����|����|�����|���|������|����|�|������|���|������|������|���|�|���|���|�|��|���|�����|�|�|�������)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem . $step1list[$suffix];
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . 'I';
        }
        if (preg_match($exceptS2, $w)) {
            $w = $w . 'I�';
        }

        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }

//Step S2. 7 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|������|�����|�������|�������|������|�������)$/';
    $exceptS1 = '/^(��|��|��|��|��|��|�|�)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem . $step1list[$suffix];
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . '��';
        }

        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }

//Step S3. 7 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|����|���|�����|�����|����|�����)$/';
    $exceptS1 = '/^(������|����|����|���|������|���|������|���|������|����|�������|����|���|�������|������|������|������|������|����|��|������)$/';
    $exceptS2 = '/^(��|��|��|���������|���|��������|���|��|�|�|������|���|���)$/';

    if ($w == "���") {
        $w = "��";
        return $w;
    }
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem . $step1list[$suffix];
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . '�';
        }

        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }


//Step S4. 7 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|�����|����|������|�����|�����|������)$/';
    $exceptS1 = '/^(������|����|���|������|���|������|���|������|����|�������|����|���|�������|������|������|������|������|����|��|������)$/';

    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem . $step1list[$suffix];
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . '�';
        }
        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }

//Step S5. 11 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|�����|����|����|�����|�����|������|����|�����|����|�����)$/';
    $exceptS1 = '/^(�|�|��|��|��|��|��|��|��|��|��|��|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���|���)$/';
    $exceptS2 = '/^(����|�������|���|��|������|���|���)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem . $step1list[$suffix];
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . '���';
        }
        if (preg_match($exceptS2, $w)) {
            $w = $w . '�';
        }
        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }

//Step S6. 6 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|�����|�����|�����|������|�����)$/';
    $exceptS1 = '/^(��������|������|�������|�����|��������|�������|�����)$/';
    $exceptS2 = '/^(��|������|�������|�����|������)$/';
    $exceptS3 = '/^(����|��������)$/';
    $exceptS4 = '/^(����������|��������|�������)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem;
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = str_replace('��', "", $w);
        }
        if (preg_match($exceptS2, $w)) {
            $w = $w . "���";
        }
        if (preg_match($exceptS3, $w)) {
            $w = $w . "�";
        }
        if (preg_match($exceptS4, $w)) {
            $w = str_replace('��', "", $w);
        }
        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }

//Step S7. 4 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|������|������|�������)$/';
    $exceptS1 = '/^(�|�)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem;
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . "A���";
        }

        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }


//Step S8. 8 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|����|����|�����|�����|�����|�����|������)$/';
    $exceptS1 = '/^(����|����|��|����|���|���|����|����|���|�����|������|��|�|��|�|��|���|����|��|����|�|�����|�����|����|����|�|���|������|����|���|����|�|��|���������)$/';
    $exceptS2 = '/^(�|���|����|��|�|�������|����|���|������|���|�����|�|��|���|������)$/';
    $exceptS3 = '/(���)$/';// for words like ��������������, ������������ etc
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem;
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . "��";
        }
        if (preg_match($exceptS2, $w)) {
            $w = $w . "���";
        }
        if (preg_match($exceptS3, $w)) {
            $w = $w . "���";
        }
        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }

//Step S9. 3 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|����|�����)$/';
    $exceptS1 = '/^(����|��|���|���)$/';
    $exceptS2 = '/(�|�����)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem;
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . "��";
        }
        if (preg_match($exceptS2, $w)) {
            $w = $w . "��";
        }
        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }


//Step S10. 4 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|�����|����|����)$/';
    $exceptS1 = '/^(�|��|���|�|�����|���|����)$/';
    if (preg_match($re, $w, $match)) {
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem;
        $test1 = false;
        if (preg_match($exceptS1, $w)) {
            $w = $w . "���";
        }

        return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
        exit;
    }


//Step1
    $numberOfRulesExamined++;
    $re = '/(.*)(�����|������|������|������|�������|�������|�������|������|�������|������|�����|������|�������|��������|��������|�����|�������|������|�������|�����|�������|������|������|�������|�����|�������|������|�������|���|�����|����|�����|��������|����������|���������|����������|�������|���������|��������|���������)$/';


    if (preg_match($re, $w, $match)) {
        //debug($w,1);
        $stem = $match[1];
        $suffix = $match[2];
        $w = $stem . $step1list[$suffix];
        $test1 = false;

    }


    // Step 2a. 2 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|����)$/';
    if (preg_match($re, $w, $match)) {
        //debug($w,"2a");
        $stem = $match[1];
        $w = $stem;
        $re = '/(��|���|���|�����|�����|�����|�����|���|���|�����)$/';
        if (!preg_match($re, $w)) {
            $w = $w . "��";
        }


    }

    //Step 2b. 2 stems
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $exept2 = '/(��|��|���|��|���|���|�����|���)$/';
        if (preg_match($exept2, $w)) {
            $w = $w . '��';
        }

    }

    //Step 2c
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|�����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;

        $exept3 = '/(���|������|�����|���|����|��|�|��|��|���|����|��|��|����|��)$/';
        if (preg_match($exept3, $w)) {
            $w = $w . '���';
        }

    }

    //Step 2d
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|���)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept4 = '/^(�|�|��|���|�|�|��|���)$/';
        if (preg_match($exept4, $w)) {
            $w = $w . '�';
        }

    }

    //Step 3
    $numberOfRulesExamined++;
    $re = '/^(.+?)(��|���|���)$/';
    if (preg_match($re, $w, $fp)) {
        $stem = $fp[1];
        $w = $stem;
        $re = '/' . $v . '$/';
        $test1 = false;
        if (preg_match($re, $w)) {
            $w = $stem . '�';
        }
    }

    //Step 4
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|���|����|����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;

        $test1 = false;
        $re = '/' . $v . '$/';
        $exept5 = '/^(��|��|���|����|�������|��|����|�����|���|����|���|����|����|������|�����|����|����|�������|����|����|���|���|�������|����|����|������|������|�������|������|����|�����|����|����|�����|�����|���)$/';
        if (preg_match($re, $w) || preg_match($exept5, $w)) {
            $w = $w . '��';
        }
    }

    //step 5a
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���)$/';
    $re2 = '/^(.+?)(�����|�����|������|�����|�������)$/';
    if ($w == "�����") {
        $w = "����";

    }

    if (preg_match($re2, $w)) {
        preg_match($re2, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;
    }
    $numberOfRulesExamined++;
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept6 = '/^(����|����|����|�����|����|���|���|���|����|���|���|�)$/';
        if (preg_match($exept6, $w)) {
            $w = $w . "��";
        }
    }

    //Step 5b
    $numberOfRulesExamined++;
    $re2 = '/^(.+?)(���)$/';
    $re3 = '/^(.+?)(�����|�����|������|�������|������|��������|������|�����|�������|�����|�������)$/';

    if (preg_match($re3, $w)) {
        preg_match($re3, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $re3 = '/^(��|��)$/';
        if (preg_match($re3, $w)) {
            $w = $w . "����";
        }
    }
    $numberOfRulesExamined++;
    if (preg_match($re2, $w)) {
        preg_match($re2, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $re2 = '/' . $v2 . '$/';
        $exept7 = '/^(�����|�����|�����|�|�������|�|�������|������|������|�����|������|�|��������|�|���|�|�����|��|�����|������|��������|�����|�������|���|�����|����|��������|�|������|��|���|���|���|���|����|��������|���|���|������|�|����|��|����|���|���|������|�����|���|���|��|����|����|����|�|��|����|�����|����|����|�����|����|����|������|���|����|�������|������|������|����|����|�����|���|�����������|�������|����|�������|���|�����������|�����������|����|��������|��������|������|�������|�����|������|����|�������|�������|����|���|���|������|������|���������|�������)$/';
        if (preg_match($re2, $w) || preg_match($exept7, $w)) {
            $w = $w . "��";
        }
    }

    //Step 5c
    $numberOfRulesExamined++;
    $re3 = '/^(.+?)(���)$/';
    $re4 = '/^(.+?)(�����)$/';

    if (preg_match($re4, $w)) {
        preg_match($re4, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;
    }
    $numberOfRulesExamined++;
    if (preg_match($re3, $w)) {
        preg_match($re3, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $re3 = '/' . $v2 . '$/';
        $exept8 = '/(��|���|���|���|����|��|���|���|���|�����|���|���|���|��|���|���|����|���|����|���|���|��|���|���|���|���|���|���|���|���|����)$/';
        $exept9 = '/^(����|���|����|���|��|��|��|���|�����|���|��|���|����|���|���|�������|����|����|����|���|�|�|��|����|�)$/';

        if (preg_match($re3, $w) || preg_match($exept8, $w) || preg_match($exept9, $w)) {
            $w = $w . "��";
        }
    }

    //Step 5d
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|�����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept10 = '/^(���)$/';
        $exept11 = '/(���)$/';
        if (preg_match($exept10, $w)) {
            $w = $w . "���";
        }
        if (preg_match($exept11, $w)) {
            $w = $w . "���";
        }
    }

    //Step 5e
    $numberOfRulesExamined++;
    $re = '/^(.+?)(������|�������)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept11 = '/^(��)$/';
        if (preg_match($exept11, $w)) {
            $w = $w . "�����";
        }
    }

    //Step 5f
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����)$/';
    $re2 = '/^(.+?)(�����)$/';

    if (preg_match($re2, $w)) {
        preg_match($re2, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $re2 = '/^(�|��|����|�����|������|�������)$/';
        if (preg_match($re2, $w)) {
            $w = $w . "����";
        }
    }
    $numberOfRulesExamined++;
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept12 = '/^(��|��|�����|�|�|�|�������|��|���|���)$/';
        if (preg_match($exept12, $w)) {
            $w = $w . "���";
        }
    }

    //Step 5g
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|����|���)$/';
    $re2 = '/^(.+?)(�����|������|�����)$/';

    if (preg_match($re2, $w)) {
        preg_match($re2, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;
    }
    $numberOfRulesExamined++;
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept13 = '/(����|�����|����|��|��|���)$/';
        $exept14 = '/^(����|�|���������|�����|����|)$/';
        if (preg_match($exept13, $w) || preg_match($exept14, $w)) {
            $w = $w . "��";
        }
    }


    //Step 5h
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|�����|����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept15 = '/^(������|���|���|�����|����|�����|������|���|�|���|�|�|���|�����|�������|��|���|����|������|��������|��|��������|�������|���|���)$/';
        $exept16 = '/(�����|����|������|����|������|����|�����|���|���|���|��|����)$/';
        if (preg_match($exept15, $w) || preg_match($exept16, $w)) {
            $w = $w . "���";
        }
    }

    //Step 5i
    $re = '/^(.+?)(���|����|���)$/';
    $numberOfRulesExamined++;
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept17 = '/^(���|������)$/';
        $exept20 = '/(����)$/';
        $exept18 = '/^(�����|�����|����|����|�|���|��|����|������|�����|����|�����|����|������|������|���|����|�����|����|����|�����|��������|����|����|�|����|���|����|������|����|����|�����|����|��|����|��������|�������|�|���|�����|���|�|��|�)$/';
        $exept19 = '/(��|���|����|��|��|��|��|��|���|����)$/';

        if ((preg_match($exept18, $w) || preg_match($exept19, $w))
            && !(preg_match($exept17, $w) || preg_match($exept20, $w))
        ) {
            $w = $w . "��";
        }
    }


    //Step 5j
    $numberOfRulesExamined++;
    $re = '/^(.+?)(���|����|���)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept21 = '/^(�|������|�������|������|�������|�����)$/';
        if (preg_match($exept21, $w)) {
            $w = $w . "��";
        }
    }

    //Step 5k
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept22 = '/^(���|��|���|��|���|�����|�����|����|�������|������)$/';
        if (preg_match($exept22, $w)) {
            $w = $w . "���";
        }
    }

    //Step 5l
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|������|������)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept23 = '/^(�|�|���|�����������|���������|����)$/';
        if (preg_match($exept23, $w)) {
            $w = $w . "���";
        }
    }

    //Step 5l
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|������|������)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
        $test1 = false;

        $exept24 = '/^(��������|�|�|������|��|��������|�����)$/';
        if (preg_match($exept24, $w)) {
            $w = $w . "���";
        }
    }

    // Step 6
    $numberOfRulesExamined++;
    $re = '/^(.+?)(����|�����|�����)$/';
    $re2 = '/^(.+?)(�|�����|����|���|����|��|��|����|����|��|�|��|���|����|����|��|����|�|�����|�������|�����|�����|�������|��������|������|�������|������|���������|��������|�������|������|�������|�����|�����|��������|�������|�������|�|����|����|����|�����|������|�������|������|�����|���|�����|����|��|����|�����|����|����|�����|���|�|��|����|�������|�����|������|�����|�����|��������|��|�������|������|�����|������|����|��|�����|�������|���|������|������|���|�����|������|�|��|�|��)$/';
    if (preg_match($re, $w, $match)) {
        //debug($w,6);
        $stem = $match[1];
        $w = $stem . "��";
    }
    $numberOfRulesExamined++;
    if (preg_match($re2, $w) && $test1) {
        //debug($w,"6-re2");
        preg_match($re2, $w, $match);
        $stem = $match[1];
        $w = $stem;
    }

    // Step 7 (����������)
    $numberOfRulesExamined++;
    $re = '/^(.+?)(�����|�����|����|����|����|����|����|����)$/';
    if (preg_match($re, $w)) {
        preg_match($re, $w, $match);
        $stem = $match[1];
        $w = $stem;
    }


    return returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined);
    exit;
}


function returnStem($w, $w_CASE, $encoding_changed, $numberOfRulesExamined)
{
    ;
//convert case back to initial by reading $w_CASE
    $unacceptedLetters = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
    $acceptedLetters = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
    for ($i = 0; $i <= strlen($w); $i++) {
        if ($w_CASE[$i] == 1) {
            for ($k = 0; $k <= 32; $k = $k + 1) {
                if ($w[$i] == $acceptedLetters[$k]) {
                    $w[$i] = $unacceptedLetters[$k];
                }
            }
        } else if ($w_CASE[$i] == 2) {
            $w[$i] = "�";
        }
    }
    if ($encoding_changed == TRUE) {
        $w = mb_convert_encoding($w, "UTF-8", "ISO-8859-7");
    }

    $returnResults = array();
    $returnResults[0] = $w;
    $returnResults[1] = $numberOfRulesExamined;
    return $returnResults;
}

?>
