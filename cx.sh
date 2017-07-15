#!/bin/sh

if [ $1 == "push" ]; then

    git pull origin master
    git add .
    git commit -m "$2"
    git push origin master

elif [ $1 == "update-base" ]; then

    git pull base master
    git push origin master

fi