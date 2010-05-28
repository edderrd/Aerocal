#!/usr/bin/env python

import os
import re
import sys
    
class PhrasesFinder:
    
    files = list();
    languageFiles = list()
    languageFileExtension = "csv"
    
    def __init__(self, directoryList, fileExtensions):
        assert type(directoryList)==list
        assert type(fileExtensions)==list
        
        self.directoryList = directoryList
        self.fileExtensions = fileExtensions
        self.files = self._getFiles();
        self.pattern = '_\([a-zA-Z_0-9 "\']+\)'
        self.progPattern = re.compile(self.pattern)
        self.languageFolder = self.directoryList[0].replace("/application", "") + 'languages'

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

    def getLanguageFiles(self):
        self.languageFiles = list()
        f = os.popen(" cd "+self.languageFolder+"; find -iname '*."+self.languageFileExtension+"'")
        for i in f:
            if i is not "":
                if not i.startswith("./en"):
                    self.languageFiles.append(i.replace("\n", "").replace("./", self.languageFolder+"/"))
        return self.languageFiles


class FileHandler:
    
    def __init__(self, filePath):
        self.filePath = filePath

    def _getExistingPhrases(self, filePath):
        f = open(filePath, "r+")
        phrases = dict()
        
        for line in f:
            line = line.replace("\n", "")
            if (line is not ""):
                word, definition = line.split(";")
                phrases[word] = definition.replace("\n", "")
        f.close()
        return phrases

    def saveBasePhrases(self, phrases):
        # TODO combine phares with file
        if phrases:
            f = open(self.filePath, "w")
            for phrase in phrases:
                f.write(phrase +";"+ phrase + "\n")
            f.close()

    def updateLanguageFiles(self, languageFiles, phrases):
        if not languageFiles:
            print "No languange files defined"
            exit(-1)
        if not phrases:
            print "No phrases passed"
            exit(-1)
            
        for languageFile in languageFiles:
            filePhrases = self._getExistingPhrases(languageFile)
            f = open(languageFile, "w")
            
            if filePhrases:
                fileWords = filePhrases.keys()
                
                for phrase in phrases:
                    if phrase in fileWords:
                        f.write(phrase+";"+ filePhrases[phrase]+"\n")
                    else:
                        f.write(phrase+";"+phrase+"\n")
            else:
                for phrase in phrases:
                    f.write(phrase+";"+ phrase +"\n")
            print("File: " + languageFile + " updated")
            f.close()

                   
if __name__ == "__main__":    
    args = sys.argv
    
    if len(args) < 2:
        print "Usage <base project path>";
        exit(1)
    
    pf = PhrasesFinder([args[1]+'/application'], ['phtml', 'php'])
    phrases = pf.getPhrases()
    print "Found " + str(len(phrases)) + " phrases"
    
    fileHandler = FileHandler(args[1]+'/languages/en/default.csv')
    fileHandler.saveBasePhrases(phrases)
    fileHandler.updateLanguageFiles(pf.getLanguageFiles(), phrases)

    print "Phrases saved on " + args[1]+ "/languages/en/default.csv"
    
