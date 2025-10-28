<img src="{{ $src() }}" 
     alt="{{ $alt }}" 
     class="{{ $class }}" 
     style="{{ $style ?? '' }}"
     @if($width) width="{{ $width }}" @endif
     @if($height) height="{{ $height }}" @endif
     @if($loading) loading="{{ $loading }}" @endif>