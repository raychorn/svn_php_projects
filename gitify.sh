#!/bin/bash

git init
find * -size +4M -type f -print >> .gitignore
git add -A
git commit -m "first commit"
git branch -M main
git remote add origin https://raychorn:47236c48faeebce059e8a68707130130e5f07723@github.com/raychorn/svn_php_projects.git
git push -u origin main
