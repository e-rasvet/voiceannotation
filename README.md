Source code repository
=====================
https://github.com/e-rasvet/moodle_filter_voiceannotation

Short Description
=================
It is Moodle vice annotation filter which allow to add voice and text annotations for teachers in Journal module students text.   

Long Description
===============
It is Moodle vice annotation filter which allow to add voice and text annotations for teachers in Journal module students text.   
Example of source code of annotation:
{{VOICEANNOTATION:TEXT=journal entry:ATT=534147385:COMM=Hello. How are you today? }}
This text be added to entry automatically and allow the filter to parse this tag to media player with popup window. 

For adding voice annotation, you need to activate filter on your Moodle settings page, and highlight any text in journal entry. You will see microphone button in right-bottom corner of your browser screen. Click it and record your voice annotation. 

Version
=======
1.0

Configuration
=============
When the filter be installed, you need to select speech to text core for this filter. If you choose Amazon transcribe, you need to set public and private keys.

How to get Amazon Transcribe Access key
=======================================

1. You need to sign up amazon transcribe account:
https://portal.aws.amazon.com/billing/signup#/start
2. Login.
3. Go to AWS IAM console https://console.aws.amazon.com/iam/home#/home
4. Create new user
5. Give new user role "AmazonTranscribeFullAccess"
6. And create new access key: User -> click to username -> Security
credentials -> Create access key.


Documentation
=============
Download the zip or clone the repo.

Edit all the files in this directory and its subdirectories and change
all the instances of the string "voiceannotation".

Place the plugin folder folder into the /filter folder of the moodle directory.

Visit Settings > Site Administration > Notifications, and let Moodle guide you through the install.

Questions and suggestions
=========================
Igor Nikulin dyhanie.istiny@gmail.com

Moodle
======
Tested in 3.4
Tested on Chrome

Copyright
=========

2020 prof. Pual Daniels, Igor Nikulin, Kochi-Tech.ac.jp

