#!/bin/sh

echo 'HTTP/1.1 200 OK'
echo

while read LINE
do
    echo "$LINE"
done <<EOF
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>3</title>
    <style type="text/css">
        a { color: blue } /* цвет ссылок */
        .one { text-decoration: none } /* убираем подчеркивание */
        .one:hover { text-decoration: underline } /* добавляем подчеркивание при наведении */
    </style>
</head>
<body>

<script type="text/javascript">
function active_link_color(a) {
  var aa = document.getElementsByTagName('a');
  for(var i in aa) {
    if(aa[i] != a && aa[i].className == 'one') {
      aa[i].style.color = 'blue';
      aa[i].style.backgroundColor = 'white';
    }
  }
  a.style.color='white';
  a.style.backgroundColor = 'blue';
}
</script>

<pre>
EOF

export $(echo "$QUERY_STRING" | tr "&" " ")

JPEGDATA="jpegdata"
DEVICEID=$(txtconfig /etc/streams.ini common deviceid)

TMPDIRNAME="/tmp/${DISC}_${DEVICEID}_${JPEGDATA}"
[ ! -x ${TMPDIRNAME} ] && ln -s ${FMEDIA}/${DEVICEID}/${JPEGDATA}/ ${TMPDIRNAME}

# image name format is 2017-07-06_22-27-41_nnnnnnnnn.jpeg
# file name length without .jpeg is 29 symbols

PASSBASE_CSV="${FMEDIA}/${DEVICEID}/passbase.csv"

tac ${PASSBASE_CSV} | while read LINE
do
    JPEGNAME=${LINE##*;}
    NAMEONLYLEN=$((${#JPEGNAME} - 5))
    JPEGNAMEONLY=${JPEGNAME:0:$NAMEONLYLEN}
    echo -n '<a href="'"${TMPDIRNAME}/${JPEGNAME}"'" target="frame_image"'
    echo -n ' class="one"'
    echo -n ' onclick="'"active_link_color(this);parent.document.getElementById('fr_image').src='';"'"'
    echo -n '>'
    echo -n ${JPEGNAMEONLY}
    echo '</a>'
done

while read LINE
do
    echo "$LINE"
done <<EOF
</pre>

</body>
</html>
EOF

echo -ne "\r\r"