<?php class BanglaDate {
    private $timestamp;
    private $morning;
    private $engHour;
    private $engDate;
    private $engMonth;
    private $engYear;
    private $bangDate;
    private $bangMonth;
    private $bangYear;

    function __construct($timestamp, $hour = 6) {
        $this->BanglaDate($timestamp, $hour);
    }

    function BanglaDate($timestamp, $hour = 6) {
        $this->engDate = date('d', $timestamp);
        $this->engMonth = date('m', $timestamp);
        $this->engYear = date('Y', $timestamp);
        $this->morning = $hour;
        $this->engHour = date('G', $timestamp);
        $this->calculate_date();
        $this->calculate_year();
        $this->convert();
    }

    function calculate_date() {
        if ($this->engMonth == 1) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "পৌষ";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "পৌষ";
                }
            } elseif ($this->engDate < 14 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "পৌষ";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "পৌষ";
                }
            } elseif ($this->engDate == 14) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 13;
                    $this->bangMonth = "মাঘ";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "পৌষ";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 13;
                    $this->bangMonth = "মাঘ";
                } else {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "মাঘ";
                }
            }
        } elseif ($this->engMonth == 2) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 18;
                    $this->bangMonth = "মাঘ";
                } else {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "মাঘ";
                }
            } elseif ($this->engDate < 13 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 18;
                    $this->bangMonth = "মাঘ";
                } else {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "মাঘ";
                }
            } elseif ($this->engDate == 13) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 12;
                    $this->bangMonth = "ফাল্গুন";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "মাঘ";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 12;
                    $this->bangMonth = "ফাল্গুন";
                } else {
                    $this->bangDate = $this->engDate - 13;
                    $this->bangMonth = "ফাল্গুন";
                }
            }
        } elseif ($this->engMonth == 3) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    if ($this->is_leapyear()) $this->bangDate = $this->engDate + 17; else $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "ফাল্গুন";
                } else {
                    if ($this->is_leapyear()) $this->bangDate = $this->engDate + 16; else $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "ফাল্গুন";
                }
            } elseif ($this->engDate < 15 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    if ($this->is_leapyear()) $this->bangDate = $this->engDate + 17; else $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "ফাল্গুন";
                } else {
                    if ($this->is_leapyear()) $this->bangDate = $this->engDate + 16; else $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "ফাল্গুন";
                }
            } elseif ($this->engDate == 15) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "চৈত্র";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "ফাল্গুন";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "চৈত্র";
                } else {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "চৈত্র";
                }
            }
        } elseif ($this->engMonth == 4) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "চৈত্র";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "চৈত্র";
                }
            } elseif ($this->engDate < 14 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "চৈত্র";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "চৈত্র";
                }
            } elseif ($this->engDate == 14) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 13;
                    $this->bangMonth = "বৈশাখ";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "চৈত্র";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 13;
                    $this->bangMonth = "বৈশাখ";
                } else {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "বৈশাখ";
                }
            }
        } elseif ($this->engMonth == 5) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "বৈশাখ";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "বৈশাখ";
                }
            } elseif ($this->engDate < 15 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "বৈশাখ";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "বৈশাখ";
                }
            } elseif ($this->engDate == 15) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                } else {
                    $this->bangDate = 31;
                    $this->bangMonth = "চৈত্র";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                } else {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                }
            }
        } elseif ($this->engMonth == 6) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                }
            } elseif ($this->engDate < 15 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 17;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                } else {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                }
            } elseif ($this->engDate == 15) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "আষাঢ়";
                } else {
                    $this->bangDate = 31;
                    $this->bangMonth = "জ্যৈষ্ঠ";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "আষাঢ়";
                } else {
                    $this->bangDate = $this->engDate - 13;
                    $this->bangMonth = "আষাঢ়";
                }
            }
        } elseif ($this->engMonth == 7) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "আষাঢ়";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "আষাঢ়";
                }
            } elseif ($this->engDate < 16 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "আষাঢ়";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "আষাঢ়";
                }
            } elseif ($this->engDate == 16) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "শ্রাবণ";
                } else {
                    $this->bangDate = 31;
                    $this->bangMonth = "আষাঢ়";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "শ্রাবণ";
                } else {
                    $this->bangDate = $this->engDate - 16;
                    $this->bangMonth = "শ্রাবণ";
                }
            }
        } elseif ($this->engMonth == 8) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "শ্রাবণ";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "শ্রাবণ";
                }
            } elseif ($this->engDate < 16 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "শ্রাবণ";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "শ্রাবণ";
                }
            } elseif ($this->engDate == 16) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "ভাদ্র";
                } else {
                    $this->bangDate = 31;
                    $this->bangMonth = "শ্রাবণ";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "ভাদ্র";
                } else {
                    $this->bangDate = $this->engDate - 16;
                    $this->bangMonth = "ভাদ্র";
                }
            }
        } elseif ($this->engMonth == 9) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "ভাদ্র";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "ভাদ্র";
                }
            } elseif ($this->engDate < 16 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "ভাদ্র";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "ভাদ্র";
                }
            } elseif ($this->engDate == 16) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "আশ্বিন";
                } else {
                    $this->bangDate = 31;
                    $this->bangMonth = "ভাদ্র";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "আশ্বিন";
                } else {
                    $this->bangDate = $this->engDate - 16;
                    $this->bangMonth = "আশ্বিন";
                }
            }
        } elseif ($this->engMonth == 10) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "আশ্বিন";
                } else {
                    $this->bangDate = $this->engDate + 14;
                    $this->bangMonth = "আশ্বিন";
                }
            } elseif ($this->engDate < 16 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "আশ্বিন";
                } else {
                    $this->bangDate = $this->engDate + 14;
                    $this->bangMonth = "আশ্বিন";
                }
            } elseif ($this->engDate == 16) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "কার্তিক";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "আশ্বিন";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "কার্তিক";
                } else {
                    $this->bangDate = $this->engDate - 16;
                    $this->bangMonth = "কার্তিক";
                }
            }
        } elseif ($this->engMonth == 11) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "কার্তিক";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "কার্তিক";
                }
            } elseif ($this->engDate < 15 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "কার্তিক";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "কার্তিক";
                }
            } elseif ($this->engDate == 15) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "অগ্রহায়ণ";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "কার্তিক";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "অগ্রহায়ণ";
                } else {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "অগ্রহায়ণ";
                }
            }
        } elseif ($this->engMonth == 12) {
            if ($this->engDate == 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "অগ্রহায়ণ";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "অগ্রহায়ণ";
                }
            } elseif ($this->engDate < 15 && $this->engDate > 1) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate + 16;
                    $this->bangMonth = "অগ্রহায়ণ";
                } else {
                    $this->bangDate = $this->engDate + 15;
                    $this->bangMonth = "অগ্রহায়ণ";
                }
            } elseif ($this->engDate == 15) {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "পৌষ";
                } else {
                    $this->bangDate = 30;
                    $this->bangMonth = "অগ্রহায়ণ";
                }
            } else {
                if ($this->engHour >= $this->morning) {
                    $this->bangDate = $this->engDate - 14;
                    $this->bangMonth = "পৌষ";
                } else {
                    $this->bangDate = $this->engDate - 15;
                    $this->bangMonth = "পৌষ";
                }
            }
        }
    }

    function is_leapyear() {
        if ($this->engYear % 400 == 0 || ($this->engYear % 100 != 0 && $this->engYear % 4 == 0)) {
            return true;
        } else {
            return false;
        }
    }

    function calculate_year() {
        if ($this->engMonth >= 4) {
            if ($this->engMonth == 4 && $this->engDate < 14) {
                $this->bangYear = $this->engYear - 594;
            } elseif ($this->engMonth == 4 && $this->engDate == 14 && $this->engHour <= 5) {
                $this->bangYear = $this->engYear - 594;
            } elseif ($this->engMonth == 4 && $this->engDate == 14 && $this->engHour >= 6) {
                $this->bangYear = $this->engYear - 593;
            } else {
                $this->bangYear = $this->engYear - 593;
            }
        } else $this->bangYear = $this->engYear - 594;
    }

    function convert() {
        $this->bangDate = $this->bangla_number($this->bangDate);
        $this->bangYear = $this->bangla_number($this->bangYear);
    }

    function bangla_number($int) {
        $engNumber = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');
        $converted = str_replace($engNumber, $bangNumber, $int);
        return $converted;
    }

    function set_time($timestamp, $hour = 6) {
        $this->BanglaDate($timestamp, $hour);
    }

    function get_date() {
        return array($this->bangDate, $this->bangMonth, $this->bangYear);
    }

    function fFormatDate($BDDate) {
        $en = array("AM", "PM", "am", "pm", "Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $bn = array("পূর্বাহ্ণ", "অপরাহ্ণ", "পূর্বাহ্ণ", "অপরাহ্ণ", "শনিবার", "রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর", "০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯");
        $BDDate = str_replace($en, $bn, $BDDate);
        return $BDDate;
    }
} ?>
