$word = New-Object -ComObject Word.Application
$word.Visible = $false
$doc = $word.Documents.Open('c:\Users\vijay\Downloads\vj\document\Lab_Record_1_to_85.docx', $false, $true)
$section = $doc.Sections.Item(1)
$footer = $section.Footers.Item(1) # wdHeaderFooterPrimary = 1
Write-Host "Footer text: [" $footer.Range.Text.Trim() "]"
Write-Host "Footer fields count: " $footer.Range.Fields.Count
if ($footer.Range.Fields.Count -gt 0) {
    Write-Host "Field 1 code: " $footer.Range.Fields.Item(1).Code.Text.Trim()
    Write-Host "Field 1 result: " $footer.Range.Fields.Item(1).Result.Text.Trim()
}
$doc.Close([ref]0)
$word.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($word) | Out-Null

