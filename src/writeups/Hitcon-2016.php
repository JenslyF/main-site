<!DOCTYPE html>
<html lang="en">


<?php
include('../includes/headers.php');
include('../includes/navbar.php');
include('../includes/footers.php');
print getHeaders('Hitcon-2016',1);
?>
<body>

    <!-- Navigation -->
    <?php
        print getNavbar(1);
    ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" >
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1>HITCON CTF 2016</h1>
                        <h2><img src="../img/hitcon-logo.png" alt="tum" align="right"></h2>
                        <span class="meta">Posted by <a href="#">kablaa</a> October, 2016</span>

                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- begn challenge list -->
    <div class="container">
        <div class = "row">
                <div class="post-body col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1">
                    <div claa = "list-group">
                        <a href="#flame" class = "list-group-item">Flame <span class ="badge">PPC-150</span></a>
                        <a href="#hand-crafted" class = "list-group-item">Handcrafted pyc <span class ="badge">Reversing-50</span></a>
                    </div>
                </div>
        </div>
    </div>
    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="post-body col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1">
                    <h1 id="flame">Flame (PPC-150)</h1>

                    This was my first encounter with Power PC, so this challenge was definitely fun learning experience for me. Here is my best attempt at the decompiled source code.
                    <pre>
                        <code class="language-C">
#include &ltstdio.h&gt
#include &ltstdlib.h&gt
#include &ltstring.h&gt
int global_values[] = {0xCFE,0x859,0x95D,0x871,0x40D,6,0xADE,0xFA8,0x561,0x9DA, \
    0x878,0x682,0xFA9,0xF5F,0x25E,0xDB0,0xFBF,0xBC6,0xD38,0x95D,0xD09,0x7ED,0x307, \
    0x1C0,0x399,0x956,0xA45,0x292,0xC8A,0x92F,0x4A,0x964,0x194,0x9DA,0x11F};
int main(){
    char input[35];
    int *checked_against = global_values;
    int i;
    //print welcome string
    scanf("%s",input);
    if(strlen(input) == 35){
        srand(7777);
        for(i = 0; i &lt 35;  i++){
            int random = rand();
            char y = input[i]^clrlwi(random);
            input[i]= y;
        }
        for(i=0; i&lt 35; i++){
            if(input[i] != checked_against[i]){
                goto FAIL;
            }
        }
        printf("Good job!! now you can submit your flag :)");
        return 0;
    }
FAIL:
    printf("Your flag is incorrect :(");
    return 0;
}
                        </code>
                    </pre>

                    There is a global array containing values that are checked against user input.
                    <img src="../img/hitcon.2016.flame.checked_against.png" alt="" />

                    I'm not sure what the C equivalent of <code>clrlwi</code> is, but what that instruction does is clear out the most significant bits of of its argument. So <code>clrlwi(0xdeadbeef)</code> would return <code>0x000000eef</code>. Because <code>rand</code> is seeded by <code>7777</code>, I can just reproduce this to get the random values that will be <code>xor</code>d with user input. Here is my <code>Get_Rands.c</code>

                    <pre>
                        <code class="language-c">
#include &ltstdio.h&gt
#include &ltstdlib.h&gt

int main(int argc, const char *argv[])
{
    srand(7777);
    int i;
    for (i = 0; i &lt 35; i++) {
       int random = rand();
       printf("%8x\n",random) ;
    }
    return 0;
}
                        </code>
                    </pre>
                    After getting these values, I just manually cleared out the most significant bits. Here is my <code>decrypt.py</code>

                    <pre>
                        <code class="language-python">
A = [0xCFE,0x859,0x95D,0x871,0x40D,6,0xADE,0xFA8,0x561,0x9DA,0x878,0x682,0xFA9,
        0xF5F,0x25E,0xDB0,0xFBF,0xBC6,0xD38,0x95D,0xD09,0x7ED,0x307,0x1C0,0x399,
        0x956,0xA45,0x292,0xC8A,0x92F,0x4A,0x964,0x194,0x9DA,0x11F]

B = [0xc96,0x830,0x929,0x812,0x462,0x068,0xaa5,0xff8,0x551,0x98d,0x84b,0x6f0,0xff9,
        0xf3c,0x201,0xdd1,0xf8d,0xbf4,0xd0b,0x910,0xd31,0x7a1,0x37e,0x19f,0x3a8,0x964,
        0xa1a,0x2e1,0xcba,0x970,0x079,0x950,0x1a1,0x9a3,0x162]


flag = ""
for i in range(0,35):
    flag += chr(A[i]^B[i])
print flag
                        </code>
                    </pre>

                    <pre>
hitcon{P0W3rPc_a223M8Ly_12_s0_345y}
                    </pre>
                    <h1 id= "hand-crafted">Handcrafted pyc (Reversing-50)</h1>
                        We were given a python file for this challege
                        <pre>
                            <code class="language-python">
#!/usr/bin/env python
# -*- coding: utf-8 -*-

import marshal, zlib, base64

exec(marshal.loads(zlib.decompress(base64.b64decode('eJyNVktv00AQXm/eL0igiaFA01IO4cIVCUGFBBJwqRAckLhEIQmtRfPwI0QIeio/hRO/hJ/CiStH2M/prj07diGRP43Hs9+MZ2fWMxbnP6mux+oK9xVMHPFViLdCTB0xkeKDFEFfTIU4E8KZq8dCvB4UlN3hGEsdddXU9QTLv1eFiGKGM4cKUgsFCNLFH7dFrS9poayFYmIZm1b0gyqxMOwJaU3r6xs9sW1ooakXuRv+un7Q0sIlLVzOCZq/XtsK2oTSYaZlStogXi1HV0iazoN2CV2HZeXqRQ54TlJRb7FUlKyUatISsdzo+P7UU1Gb1POdMruckepGwk9tIXQTftz2yBaT5JQovWvpSa6poJPuqgao+b9l5Aj/R+mLQIP4f6Q8Vb3g/5TB/TJxWGdZr9EQrmn99fwKtTvAZGU7wzS7GNpZpDm2JgCrr8wrmPoo54UqGampFIeS9ojXjc4E2yI06bq/4DRoUAc0nVnng4k6p7Ks0+j/S8z9V+NZ5dhmrJUM/y7JTJeRtnJ2TSYJvsFq3CQt/vnfqmQXt5KlpuRcIvDAmhnn2E0t9BJ3SvB/SfLWhuOWNiNVZ+h28g4wlwUp00w95si43rZ3r6+fUIEdgOZbQAsyFRRvBR6dla8KCzRdslar7WS+a5HFb39peIAmG7uZTHVm17Czxju4m6bayz8e7J40DzqM0jr0bmv9PmPvk6y5z57HU8wdTDHeiUJvBMAM4+0CpoAZ4BPgJeAYEAHmgAUgAHiAj4AVAGORtwd4AVgC3gEmgBBwCPgMWANOAQ8AbwBHgHuAp4D3gLuARwoGmNUizF/j4yDC5BWM1kNvvlxFA8xikRrBxHIUhutFMBlgQoshhPphGAXe/OggKqqb2cibxwuEXjUcQjccxi5eFRL1fDSbKrUhy2CMb2aLyepkegDWsBwPlrVC0/kLHmeCBQ=='))))
                            </code>
                        </pre>
                        After looking at the decompressed data, I realized that the header had been removed, so I compiled a <code>pyc</code> file and got the correct header. Here is my <code>Get_Bytecodes.py</code>
                        <pre>
                            <code class = "language-python">
#!/usr/bin/env python
# -*- coding: utf-8 -*-

import marshal, zlib, base64
header="\x03\xf3\x0d\x0a\xf1\x00\xf9\x57"


x = 'eJyNVktv00AQXm/eL0igiaFA01IO4cIVCUGFBBJwqRAckLhEIQmtRfPwI0QIeio/hRO/hJ/CiStH2M/prj07diGRP43Hs9+MZ2fWMxbnP6mux+oK9xVMHPFViLdCTB0xkeKDFEFfTIU4E8KZq8dCvB4UlN3hGEsdddXU9QTLv1eFiGKGM4cKUgsFCNLFH7dFrS9poayFYmIZm1b0gyqxMOwJaU3r6xs9sW1ooakXuRv+un7Q0sIlLVzOCZq/XtsK2oTSYaZlStogXi1HV0iazoN2CV2HZeXqRQ54TlJRb7FUlKyUatISsdzo+P7UU1Gb1POdMruckepGwk9tIXQTftz2yBaT5JQovWvpSa6poJPuqgao+b9l5Aj/R+mLQIP4f6Q8Vb3g/5TB/TJxWGdZr9EQrmn99fwKtTvAZGU7wzS7GNpZpDm2JgCrr8wrmPoo54UqGampFIeS9ojXjc4E2yI06bq/4DRoUAc0nVnng4k6p7Ks0+j/S8z9V+NZ5dhmrJUM/y7JTJeRtnJ2TSYJvsFq3CQt/vnfqmQXt5KlpuRcIvDAmhnn2E0t9BJ3SvB/SfLWhuOWNiNVZ+h28g4wlwUp00w95si43rZ3r6+fUIEdgOZbQAsyFRRvBR6dla8KCzRdslar7WS+a5HFb39peIAmG7uZTHVm17Czxju4m6bayz8e7J40DzqM0jr0bmv9PmPvk6y5z57HU8wdTDHeiUJvBMAM4+0CpoAZ4BPgJeAYEAHmgAUgAHiAj4AVAGORtwd4AVgC3gEmgBBwCPgMWANOAQ8AbwBHgHuAp4D3gLuARwoGmNUizF/j4yDC5BWM1kNvvlxFA8xikRrBxHIUhutFMBlgQoshhPphGAXe/OggKqqb2cibxwuEXjUcQjccxi5eFRL1fDSbKrUhy2CMb2aLyepkegDWsBwPlrVC0/kLHmeCBQ=='

y = base64.b64decode(x)
z= zlib.decompress(y)
f = open('dump.pyc','wb')
data = header + z
f.write(data)
f.close()
                            </code>
                        </pre>
                        I then ran <a href="https://github.com/wibiti/uncompyle2">uncompyle2</a> ,which did not work completely due to some errors with <code>ROT_2</code>. However, I was able to get the python bye codes
                        <pre>
0    LOAD_GLOBAL       'chr'
3   LOAD_CONST        108
6   CALL_FUNCTION_1   None
9   LOAD_GLOBAL       'chr'
12  LOAD_CONST        108
15  CALL_FUNCTION_1   None
18  LOAD_GLOBAL       'chr'
21  LOAD_CONST        97
24  CALL_FUNCTION_1   None
27  LOAD_GLOBAL       'chr'
30  LOAD_CONST        67
33  CALL_FUNCTION_1   None
36  ROT_TWO           None
37  BINARY_ADD        None
38  ROT_TWO           None
39  BINARY_ADD        None
40  ROT_TWO           None
41  BINARY_ADD        None
.....
                        </pre>

                        I noticed that there wew a bunch of characters being loaded. I figured I would see what they are.
                        <pre>
uncompyle2 dump.pyc &gt broken
                        </pre>
                        and here is my <code>fix.py</code>
                        <pre>
                            <code class= "language-python">
def isInt(s):
    try:
        int(s)
        return True
    except ValueError:
        return False

f = open('broken','rb')
data = f.read()
data = data.split('\n')
result = ""
for d in data:
    s=d.split(' ')
    x = s[len(s)-1]
    if(isInt(x) == True):
        result = result + chr(int(x))
print result
                            </code>
                        </pre>
which gave me the flag..only it was all scrambled up.
<pre>
llaC em yP aht notriv lauhcamni !eac Ini npreterP tohty ntybdocese!!!
ctihN{noy woc uoc naipmoa eldnur yP nnohttyb doceni euoy rb ria}!
napwssro :dorWp gnssadrow...elP  esa yrtaga .ni oD tonurbf etecro)= .$
</pre>
After some good old fashion trial and error, we were able to get the flag
<pre>
hitcon{Now you can compile and run Python bytecode in your brain!}
</pre>
                </div>
            </div>
        </div>
    </article>

    <hr>

    <!-- Footer -->
    <?php
        print getFooters();
    ?>

</body>

</html>
