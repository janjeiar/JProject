# JProject

* Copy "pixel.php" file into your local web server.
* Open "localhost/pixel.php" in web browser.
* Below are lists of valid input commands:
  
> I M N (e.g. I 5 6)
  - generates 2-dimensional array filled with characters (O) default white color assuming every character is a cell/pixel.
  - M >= 1 AND N <= 250
  
> C
  - clears the array and fills every cell/pixel with characters (O) default white color
  
> L X Y C (e.g. L 2 3 A)
  - fills cell/pixel of the coordinate (X,Y) with character (A)
  
> V X Y1 Y2 C (e.g. V 2 3 4 W)
  - fill series of cells/pixels vertically from coordinate (X,Y1) to (X,Y2) with character (W)
  
> H X1 X2 Y C (e.g. H 3 4 2 C)
  - fill series of cells/pixels horizontally from coordinate (X1,Y) to (X2,Y) with character (C)
  
> F X Y C (e.g. F 3 3 J)
  - fills the region (R) of cells/pixels starting from coordinate (1,1) to (X,Y) with character (J)
  
> S
  - shows the ouptup of generated/modified 2-dimensional array of cell/pixel.
  
> X
  - terminate the session.
