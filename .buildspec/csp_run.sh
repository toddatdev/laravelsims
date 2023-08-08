#!/bin/bash

build=$(aws codebuild start-build --project-name CSP-Scan | jq .build.id -r)
# Uncomment below to fail the build on CSP errors
# buildrun=$(aws codebuild batch-get-builds --ids $build | jq .builds[0].currentPhase -r)
# while [ "$buildrun" != "COMPLETED" ]; do
#     echo $buildrun
#     sleep 30
#     buildrun=$(aws codebuild batch-get-builds --ids $build | jq .builds[0].currentPhase -r)
# done
# buildresult=$(aws codebuild batch-get-builds --ids $build | jq .builds[0].buildStatus -r)
# if [ "$buildresult" = "FAILED" ]; then
#     exit 2
# fi
