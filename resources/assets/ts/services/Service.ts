abstract class Service {
    protected url: string = process.env.MIX_API_URL || ''
}

export default Service
