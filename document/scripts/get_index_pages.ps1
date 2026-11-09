$word = New-Object -ComObject Word.Application
$word.Visible = $false
$doc = $word.Documents.Open('c:\Users\vijay\Downloads\vj\document\test_index_only.docx', $false, $true)
$pages = $doc.ComputeStatistics(2)
Write-Host "Total Index Pages: $pages"
$doc.Close([ref]0)
$word.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($word) | Out-Null

