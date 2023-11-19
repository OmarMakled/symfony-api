import { Alert, AlertTitle } from '@mui/material';

const ErrorAlert = ({ error }) => {
  const isStringError = typeof error === 'string';
  return (
    <>
      {error && (
        <Alert severity="error" sx={{ marginBottom: 4 }}>
          <AlertTitle>Error</AlertTitle>
          {isStringError ? (
            <p>{error}</p>
          ) : (
            Object.entries(error).map(([key, messages]) =>
              messages.map((message, index) => (
                <div key={`${key}-${index}`}>{message}</div>
              )),
            )
          )}
        </Alert>
      )}
    </>
  );
};

export default ErrorAlert;
