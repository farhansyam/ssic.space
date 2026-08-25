<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; }
        .cert { position: relative; width: 297mm; height: 210mm; }
        .cert img.bg { position: absolute; top: 0; left: 0; width: 297mm; height: 210mm; }
        .field { position: absolute; text-align: center; transform: translate(-50%, -50%); width: 80%; left: 50%; }
    </style>
</head>
<body>
    <div class="cert">
        <img class="bg" src="{{ $backgroundPath }}">

        @foreach ($layout as $key => $pos)
            @if (isset($values[$key]) && $values[$key] !== '')
                <div
                    class="field"
                    style="top: {{ $pos['y'] ?? 50 }}%; font-size: {{ $pos['fontSize'] ?? 24 }}px; color: {{ $pos['color'] ?? '#1e293b' }}; font-weight: {{ $pos['bold'] ?? false ? 'bold' : 'normal' }};"
                >{{ $values[$key] }}</div>
            @endif
        @endforeach
    </div>
</body>
</html>
