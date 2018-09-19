#!/bin/bash
echo "Setando UID"
export UID=$UID;

echo "Subindo container com docker-compose";
docker-compose up -d