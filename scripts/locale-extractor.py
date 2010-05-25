#!/usr/env python

import os
import stat
import re
import getopt
import sys
    
class PhrasesFinder:
    
    files = list();
    
    def __init__(self, directoryList, fileExtensions):
        assert type(directoryList)==list
        assert type(fileExtensions)==list
        
        self.directoryList = directoryList
        self.fileExtensions = fileExtensions
        self.files = self._getFiles();
        self.pattern = '_\([a-zA-Z_0-9 "\']+\)'
        self.progPattern = re.compile(self.pattern)
        

    def _getFiles(self):
        rv = list();
        
        for directory in self.directoryList:
            for extension in self.fileExtensions:
                f = os.popen("cd "+directory+"; find -iname '*."+extension+"'")
                for i in f:
                    if i is not "":
                        rv.append(i.replace("\n", "").replace("./", directory+"/"))
        return rv
        
    def getPhrases(self):
        rv = dict()
        
        if not self.files:
            return rv
        else:
            for _file in self.files:
                f = open(_file, "r")
                
                for line in f:
                    result = self.progPattern.findall(line)
                    if result:
                        for phrase in result:
                            cleanPhrase = phrase.replace("_(", "").replace(")", "").replace('"', "").replace("'", "")
                            rv[cleanPhrase] = ""
                            
        if rv:
            return rv.keys();
        return list()

class FileHandler:
    
    def __init__(self, filePath, ):
        self.filePath = filePath
        
    def saveBasePhrases(self, phrases):
        # TODO combine phares with file
        if phrases:
            f = open(self.filePath, "w")
            for phrase in phrases:
                f.write(phrase +";"+ phrase + "\n")
            f.close()
                   
if __name__ == "__main__":    
    args = sys.argv
    
    if len(args) < 2:
        print "Usage <base project path>";
        exit(1)
    
    pf = PhrasesFinder([args[1]+'/application'], ['phtml', 'php'])
    phrases = pf.getPhrases()
    print "Found " + str(len(phrases)) + " phrases"
    
    basicFile = FileHandler(args[1]+'/languages/en/default.csv')
    basicFile.saveBasePhrases(phrases)
    print "Phrases saved on " + args[1]+ "/languages/en/default.csv"
    
