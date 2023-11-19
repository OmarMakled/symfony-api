import { useState } from 'react';
import { Button, styled } from '@mui/material';
import CloudUploadIcon from '@mui/icons-material/CloudUpload';

const VisuallyHiddenInput = styled('input')({
  clip: 'rect(0 0 0 0)',
  clipPath: 'inset(50%)',
  height: 1,
  overflow: 'hidden',
  position: 'absolute',
  bottom: 0,
  left: 0,
  whiteSpace: 'nowrap',
  width: 1,
});

const FileUpload = ({ onFileChange }) => {
  const [selectedFiles, setSelectedFiles] = useState(null);

  return (
    <Button
      component="label"
      variant="contained"
      startIcon={<CloudUploadIcon />}
      fullWidth
      sx={{ marginBottom: 2, marginTop: 2 }}
    >
      Upload file
      <VisuallyHiddenInput
        type="file"
        accept="image/*"
        multiple
        onChange={(e) => {
          setSelectedFiles(e.target.files);
          onFileChange(e.target.files);
        }}
      />
    </Button>
  );
};

export default FileUpload;
