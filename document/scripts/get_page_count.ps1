$word = New-Object -ComObject Word.Application
$word.Visible = $false
$docPath = "c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx"
$doc = $word.Documents.Open($docPath, $false, $true)
$pages = $doc.ComputeStatistics(2)
Write-Host "Total Word Pages: $pages"
$doc.Close([ref]0)
$word.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($word) | Out-Null

