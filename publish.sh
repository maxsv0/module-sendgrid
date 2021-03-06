#!/bin/bash
set -e

#
# Module publishing tool for MSV Repository
#
# Module publishing settings:
#   repositoryurl   - Repository URL. Default value: http://rep.msvhost.com/api/import/
#   repositorykey   - is your developer key for accessing the repository. Default value: $1
#                     NOTE! Do not hardcode repositorykey value. It can cause security issues.
#   modulename      - is current module name. Default value: $2
#   configinstall   - is a path to config.install.xml or config.xml of a current module
#   previewfile     - is a path to module preview
#
#   ************* More information can be found here https://github.com/maxsv0/repository *************
#
repositoryurl=http://rep.msvhost.com/api/import/
modulename=sendgrid
repositorykey=$1
configinstall=src/module/$modulename/config.xml
previewfile=src/content/images/module_preview/$modulename.jpg

if [ -z "$modulename" ]
  then
    echo "[ERROR] Missing Module name"
	exit 1
fi

mkdir src-temp
cp -a src/. src-temp
find src-temp/ -name .DS_Store -delete

echo "Creating $modulename.zip.."
cd src-temp
zip -r ../$modulename.zip .
cd ..

echo "Removing temp files.."
rm -R src-temp
echo "Done! $modulename.zip created successfully"

echo "=============================================="
echo "Publish archive to MSV repository: $repositoryurl"

if [ -z "$repositorykey" ]
  then
    echo "[ERROR] Missing repository KEY"
	exit 1
fi

if [ ! -f $configinstall ]
  then
    echo "[ERROR] Missing installation config file: $configinstall"
	exit 1
fi

if [ ! -f $previewfile ];
	then
      echo "[ERROR] Preview file $previewfile was not found"
	  exit 1
fi

echo "========> Module: $modulename (key :  $repositorykey)"
echo "Sending file to repository.."
response=$(curl -F "file=@$modulename.zip" -F "preview=@$previewfile" -F "config=@$configinstall" -F "module=$modulename" -F "key=$repositorykey" $repositoryurl)
echo $response

if [[ $response = *"[ERROR]"* ]]; then
    echo "[ERROR] has occurred"
    exit 1
fi

echo "[SUCCESS] upload successfully!"
exit 0