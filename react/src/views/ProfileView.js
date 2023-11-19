import { useDispatch, useSelector } from 'react-redux';
import { Carousel } from 'react-responsive-carousel';
import 'react-responsive-carousel/lib/styles/carousel.min.css';
import {
  Avatar,
  Card,
  CardActions,
  CardContent,
  Button,
  Typography,
  Grid,
} from '@mui/material';
import { clearToken } from '../store/authSlice';
import { useNavigate } from 'react-router-dom';

const ProfileView = () => {
  const dispatch = useDispatch();
  const user = useSelector((state) => state.auth.user);
  const navigate = useNavigate();

  const handleLogout = () => {
    dispatch(clearToken());
    navigate('/');
  };

  return (
    <>
      {user && (
        <Grid container spacing={2}>
          <Grid item xs={12} md={3}>
            <Card className="text-center" variant="outlined">
              <Avatar sx={{ width: 150, height: 150, mx: 'auto', mt: 4 }}>
                <img
                  src={user.avatar}
                  alt={user.name}
                  style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                />
              </Avatar>
              <CardContent className="text-left">
                <Typography variant="h6" component="div">
                  First Name:
                </Typography>
                <Typography variant="subtitle1">{user.first_name}</Typography>
                <Typography variant="h6" component="div">
                  Last Name:
                </Typography>
                <Typography variant="subtitle1">{user.last_name}</Typography>
                <Typography variant="h6" component="div">
                  Email:
                </Typography>
                <Typography variant="subtitle1">{user.email}</Typography>
              </CardContent>
              <CardActions>
                <Button color="primary" onClick={handleLogout}>
                  Logout
                </Button>
              </CardActions>
            </Card>
          </Grid>
          <Grid item xs={12} md={9}>
            <Card variant="outlined">
              <CardContent>
                {user.photos && user.photos.length > 0 ? (
                  <Carousel>
                    {user.photos.map((photo, index) => (
                      <img src={photo.url} alt={photo.name} key={index} />
                    ))}
                  </Carousel>
                ) : (
                  <Typography variant="body1">No photos available.</Typography>
                )}
              </CardContent>
            </Card>
          </Grid>
        </Grid>
      )}
    </>
  );
};

export default ProfileView;
