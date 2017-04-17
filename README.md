# Project 10 - Fortress Globitek

Time spent: 10 hours spent in total

> Objective: Create an intentionally vulnerable version of the Globitek application with a secret that can be stolen.

### Requirements

- [x] All source code and assets necessary for running app
- [x] `/globitek.sql` containing all required SQL, including the `secrets` table
- [x] GIF Walkthrough of compromise
- [x] Brief writeup about the vulnerabilities introduced below

### Vulnerabilities

There are two IDOR vulnerabilities chained together. 

First, in table `users`, administator is not careful enough to hide an authoratative user who has left the company. The former employee left an clue in his user profile that there is a secret in the country. 

Second, in the country table, there is another IDOR vulnerability that contains the real secret. 

