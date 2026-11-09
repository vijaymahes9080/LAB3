$word = New-Object -ComObject Word.Application
$word.Visible = $false
$docPath = "c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx"
$pdfPath = "c:\Users\vijay\Downloads\vj\document\rendered_test.pdf"
if (Test-Path $pdfPath) { Remove-Item $pdfPath }
$doc = $word.Documents.Open($docPath, $false, $true)
$doc.SaveAs([ref]$pdfPath, [ref]17)
$doc.Close([ref]0)
$word.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($word) | Out-Null
Write-Host "Exported rendered_test.pdf successfully!"

