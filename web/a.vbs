Set oVoice = CreateObject("SAPI.SpVoice")

set oSpFileStream = CreateObject("SAPI.SpFileStream")

Set oFSO = CreateObject("Scripting.FileSystemObject")
strFolder = oFSO.GetParentFolderName(WScript.ScriptFullName)
FileToOpen = oFSO.BuildPath(strFolder, "\mp3\tada.wav")

oSpFileStream.Open FileToOpen

oVoice.SpeakStream oSpFileStream

oSpFileStream.Close