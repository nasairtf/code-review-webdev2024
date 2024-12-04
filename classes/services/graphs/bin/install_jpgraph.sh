#!/bin/bash

# Set variables for the download and target installation path
JPGRAPH_URL="https://jpgraph.net/download/download.php?p=57"  # Replace with the actual download URL if available
INSTALL_DIR="/home/webdev2024/classes/services/graphs/lib/jpgraph"
TAR_FILE="/home/webdev2024/classes/services/graphs/bin/jpgraph.tar.gz"

# Create the installation directory if it doesn't exist
mkdir -p "$INSTALL_DIR"

# Check if wget or curl is available, and download the JpGraph package
if command -v wget > /dev/null; then
    echo "Downloading JpGraph using wget..."
    wget -O "$TAR_FILE" "$JPGRAPH_URL"
elif command -v curl > /dev/null; then
    echo "Downloading JpGraph using curl..."
    curl -L -o "$TAR_FILE" "$JPGRAPH_URL"
else
    echo "Error: Neither wget nor curl is available. Install one to proceed."
    exit 1
fi

# Unzip the downloaded file to the installation directory
echo "Unzipping JpGraph to $INSTALL_DIR..."
tar -xzf "$TAR_FILE" -C "$INSTALL_DIR" --strip-components=1

# Clean up by removing the downloaded zip file
#rm "$ZIP_FILE"

#echo "JpGraph installed successfully in $INSTALL_DIR"
