import { useSelector } from 'react-redux';
import { Link } from 'react-router-dom';

const SuccessView = () => {
  const user = useSelector((state) => state.auth.user);

  return (
    <div>
      <h2>Hi {user?.first_name} Registration Successful!</h2>
      <p>
        Click here to show profile: <Link to="/profile">Go to Profile</Link>
      </p>
    </div>
  );
};

export default SuccessView;
