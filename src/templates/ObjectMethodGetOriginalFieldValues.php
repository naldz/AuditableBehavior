/**
 * Return the original field values of this object
 */
 
public function getOriginalFieldValues($mapped = true)
{
    if (true === $mapped) {
        <?php echo '$returnValues = array(';?>
        <?php foreach ($columnNames as $index => $iColumnName): ?>
            <?php echo "'$iColumnName' => \$this->originalFieldValues[$index],"?>
        <?php endforeach; ?>
        <?php echo ');'?>
    }
    else {
        $returnValues = $this->originalFieldValues;
    }

    return $returnValues;
}